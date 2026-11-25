<?php

namespace App\Services\Social;

use App\Models\Business;
use App\Models\User;
use App\Services\Business\BusinessService;

class SocialBusinessAccessService
{
    public function __construct(
        protected BusinessService $businessService
    ) {}

    public function getBusiness(User $user, int $businessId): Business
    {
        return $this->businessService->findForUser(
            $user,
            $businessId
        );
    }

    public function authorizeConnection(Business $business): void
    {
        abort_unless(
            in_array($business->pivot->role, ['owner', 'admin'], true),
            403,
            'Only business owners and admins can connect social accounts.'
        );
    }
}
