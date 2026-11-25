<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Services\Social\SocialAccountService;
use App\Services\Social\SocialBusinessAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialOAuthController extends ApiController
{
    public function __construct(
        protected SocialAccountService $socialAccountService,
        protected SocialBusinessAccessService $businessAccess
    ) {}

    /**
     * GET /api/v1/social/{provider}/connect?business_id=1
     */
    public function connect(Request $request, string $provider)
    {
        $this->validateProvider($provider);

        $validated = $request->validate([
            'business_id' => ['required', 'integer', 'exists:businesses,id'],
        ]);

        $business = $this->businessAccess->getBusiness(
            $request->user(),
            (int) $validated['business_id']
        );

        $this->businessAccess->authorizeConnection($business);

        $state = Str::random(64);

        Cache::put(
            'social_oauth_state:' . hash('sha256', $state),
            [
                'user_id' => $request->user()->id,
                'business_id' => $business->id,
                'provider' => $provider,
            ],
            now()->addMinutes(10)
        );

        $scopes = config(
            "marketpilot.social_scopes.{$provider}",
            []
        );

        $driver = Socialite::driver($provider)
            ->stateless()
            ->scopes($scopes);

        // Google needs offline access to request a refresh token.
        if ($provider === 'google') {
            $driver->with([
                'access_type' => 'offline',
                'prompt' => 'consent',
            ]);
        }

        $authorizationUrl = $driver
            ->with(['state' => $state])
            ->redirect()
            ->getTargetUrl();

        return $this->success([
            'provider' => $provider,
            'authorization_url' => $authorizationUrl,
        ], 'Authorization URL generated successfully');
    }

    /**
     * GET /api/v1/social/{provider}/callback
     */
    public function callback(Request $request, string $provider)
    {
        if (!in_array(
            $provider,
            config('marketpilot.social_providers', []),
            true
        )) {
            return $this->frontendRedirect(
                'error',
                'Unsupported social provider.'
            );
        }

        if ($request->filled('error')) {
            return $this->frontendRedirect(
                'error',
                'Social account authorization was cancelled or denied.'
            );
        }

        $state = $request->query('state');

        if (!$state) {
            return $this->frontendRedirect(
                'error',
                'OAuth state is missing.'
            );
        }

        // Pull makes the state one-time use.
        $stateData = Cache::pull(
            'social_oauth_state:' . hash('sha256', $state)
        );

        if (
            !$stateData ||
            $stateData['provider'] !== $provider
        ) {
            return $this->frontendRedirect(
                'error',
                'Invalid or expired OAuth state.'
            );
        }

        try {
            // Recheck the user and business membership.
            $user = User::findOrFail($stateData['user_id']);

            $business = $this->businessAccess->getBusiness(
                $user,
                (int) $stateData['business_id']
            );

            $this->businessAccess->authorizeConnection($business);

            $socialUser = Socialite::driver($provider)
                ->stateless()
                ->user();

            $this->socialAccountService->saveAccount(
                $business,
                $user->id,
                $provider,
                $socialUser,
                config("marketpilot.social_scopes.{$provider}", [])
            );

            return $this->frontendRedirect(
                'success',
                'Social account connected successfully.',
                $provider
            );

        } catch (Throwable $e) {
            Log::error('Social OAuth callback failed', [
                'provider' => $provider,
                'business_id' => $stateData['business_id'],
                'exception' => $e->getMessage(),
            ]);

            return $this->frontendRedirect(
                'error',
                'Unable to connect the social account.'
            );
        }
    }

    private function validateProvider(string $provider): void
    {
        abort_unless(
            in_array(
                $provider,
                config('marketpilot.social_providers', []),
                true
            ),
            422,
            'Unsupported social provider.'
        );
    }

    private function frontendRedirect(
        string $status,
        string $message,
        ?string $provider = null
    ) {
        $url = rtrim(config('marketpilot.frontend_url'), '/')
            . '/settings/social?'
            . http_build_query([
                'status' => $status,
                'message' => $message,
                'provider' => $provider,
            ]);

        return redirect()->away($url);
    }
}
