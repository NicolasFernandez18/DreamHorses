<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Support\PermissionCatalog;


class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [];

        foreach (PermissionCatalog::sections() as $section) {
            foreach ($section as $permissionName => $label) {
                $permissions[$permissionName] = Permission::firstOrCreate(
                    ['name' => $permissionName, 'guard_name' => 'web']
                );
            }
        }

        $roles = [];
        foreach (['admin', 'boss', 'caretaker'] as $roleName) {
            $roles[$roleName] = Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web']
            );
            $roles[$roleName]->syncPermissions(PermissionCatalog::rolePermissions()[$roleName]);
        }

        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin123'),
                'phone' => '123456789',
                'address' => 'Admin Address',
                'email_verified_at' => now(),
            ]
        );

        $bossUser = User::updateOrCreate(
            ['email' => 'boss@example.com'],
            [
                'name' => 'boss',
                'password' => Hash::make('boss123'),
                'phone' => '123456789',
                'address' => 'Boss Address',
                'email_verified_at' => now(),
            ]
        );

        $caretakerUser = User::updateOrCreate(
            ['email' => 'caretaker@example.com'],
            [
                'name' => 'caretaker',
                'password' => Hash::make('caretaker123'),
                'phone' => '123456789',
                'address' => 'Caretaker Address',
                'email_verified_at' => now(),
            ]
        );

        $adminUser->syncRoles([$roles['admin']]);
        $bossUser->syncRoles([$roles['boss']]);
        $caretakerUser->syncRoles([$roles['caretaker']]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
