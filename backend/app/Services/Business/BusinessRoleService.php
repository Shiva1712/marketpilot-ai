<?php

namespace App\Services\Business;

use App\Models\Business;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class BusinessRoleService
{
    public function createDefaultRoles(Business $business): void
    {
        app(PermissionRegistrar::class)
            ->setPermissionsTeamId($business->id);

        $roles = [
            'business-owner' => [
                'business.view',
                'business.update',
                'brand.view',
                'brand.create',
                'brand.update',
                'brand.delete',
                'content.view',
                'content.create',
                'content.update',
                'content.delete',
                'content.approve',
                'campaign.view',
                'campaign.create',
                'campaign.update',
                'campaign.delete',
            ],

            'business-member' => [
                'business.view',
                'brand.view',
                'content.view',
                'content.create',
                'content.update',
                'campaign.view',
            ],

            'viewer' => [
                'business.view',
                'brand.view',
                'content.view',
                'campaign.view',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate([
                'business_id' => $business->id,
                'name' => $roleName,
                'guard_name' => 'api',
            ]);

            $role->syncPermissions($permissions);
        }
    }
}