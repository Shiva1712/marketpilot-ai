<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\SocialAccountResource;
use App\Services\Social\SocialAccountService;
use App\Services\Social\SocialBusinessAccessService;
use Illuminate\Http\Request;

class SocialAccountController extends ApiController
{
    public function __construct(
        protected SocialAccountService $socialAccountService,
        protected SocialBusinessAccessService $businessAccess
    ) {}

    /**
     * GET /api/v1/businesses/{business}/social-accounts
     */
    public function index(Request $request, int $business)
    {
        $business = $this->businessAccess->getBusiness(
            $request->user(),
            $business
        );

        $accounts = $this->socialAccountService
            ->getAccounts($business);

        return $this->success([
            'items' => SocialAccountResource::collection(
                $accounts->items()
            )->resolve(),

            'pagination' => [
                'current_page' => $accounts->currentPage(),
                'last_page' => $accounts->lastPage(),
                'per_page' => $accounts->perPage(),
                'total' => $accounts->total(),
            ],
        ], 'Social accounts retrieved successfully');
    }
}
