<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
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
        ];

        foreach ($permissions as $permission) {
            // Permission::firstOrCreate([
            //     'name' => $permission,
            //     'guard_name' => 'web',
            // ]);
             Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api',
            ]);
        }

        // These are global permission definitions.
        // Business-specific roles are created when a business is created.
    }
}