<?php

namespace App\Services\Social;

use App\Models\Business;
use App\Models\SocialAccount;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialAccountService
{
    public function getAccounts(Business $business)
    {
        return $business->socialAccounts()
            ->latest()
            ->paginate(15);
    }

    public function saveAccount(
        Business $business,
        int $userId,
        string $provider,
        SocialiteUser $socialUser,
        array $scopes = []
    ): SocialAccount {
        $expiresAt = $socialUser->expiresIn !== null
            ? now()->addSeconds($socialUser->expiresIn)
            : null;

        return SocialAccount::updateOrCreate(
            [
                'business_id' => $business->id,
                'provider' => $provider,
                'provider_account_id' => $socialUser->getId(),
            ],
            [
                'connected_by' => $userId,
                'account_name' => $socialUser->getName(),
                'username' => $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'avatar_url' => $socialUser->getAvatar(),
                'access_token' => $socialUser->token,
                'refresh_token' => $socialUser->refreshToken,
                'scopes' => $scopes,
                'expires_at' => $expiresAt,
                'status' => 'connected',
            ]
        );
    }
}
