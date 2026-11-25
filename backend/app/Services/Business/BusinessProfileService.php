<?php

namespace App\Services\Business;

use App\Models\Business;
use App\Models\BusinessProfile;

class BusinessProfileService
{
    public function getProfile(Business $business)
    {
        return $business->profile;
    }

    public function updateProfile(
        Business $business,
        array $data
    ): BusinessProfile {

        $business->profile()->updateOrCreate(
            ['business_id' => $business->id],
            $data
        );

        return $business->profile()->first();
    }
}
