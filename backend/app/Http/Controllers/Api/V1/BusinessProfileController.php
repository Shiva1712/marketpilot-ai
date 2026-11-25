<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use App\Services\Business\BusinessService;
use App\Services\Business\BusinessProfileService;
use App\Http\Requests\Api\V1\BusinessProfileRequest;

use Illuminate\Http\Request;

class BusinessProfileController extends ApiController
{
    protected $businessService;
    protected $profileService;

    public function __construct(
        BusinessService $businessService,
        BusinessProfileService $profileService
    ) {
        $this->businessService = $businessService;
        $this->profileService = $profileService;
    }

    // GET business profile
    public function show(Request $request, $business)
    {
        $business = $this->businessService->findForUser(
            $request->user(),
            $business
        );

        $profile = $this->profileService->getProfile($business);

        return $this->success(
            $profile,
            'Business profile retrieved successfully'
        );
    }

    // PUT business profile
    public function update(
        BusinessProfileRequest $request,
        $business
    ) {
        $business = $this->businessService->findForUser(
            $request->user(),
            $business
        );

        if (!in_array($business->pivot->role, ['owner', 'admin'])) {
            return $this->error(
                'You are not authorized to update this business profile.',
                null,
                403
            );
        }

        $profile = $this->profileService->updateProfile(
            $business,
            $request->validated()
        );

        return $this->success(
            $profile,
            'Business profile updated successfully'
        );
    }
}