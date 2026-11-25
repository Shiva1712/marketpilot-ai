<?php

namespace App\Services\Business;

use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BusinessService
{
    public function getAll(User $user)
    {
        return $user->businesses()
            ->with('profile')
            ->latest('businesses.created_at')
            ->paginate(15);
    }

    public function create(array $data, User $user)
    {
        return DB::transaction(function () use ($data, $user) {

            $business = Business::create($data);

            $business->members()->attach($user->id, [
                'role' => 'owner',
            ]);

            $business->profile()->create([
                'timezone' => 'UTC',
            ]);

            return $business->load('profile');
        });
    }

    public function findForUser(User $user, int $businessId)
    {
        return $user->businesses()
            ->with('profile')
            ->where('businesses.id', $businessId)
            ->firstOrFail();
    }

    public function update(Business $business, array $data)
    {
        $business->update($data);

        return $business->fresh(['profile']);
    }
}
