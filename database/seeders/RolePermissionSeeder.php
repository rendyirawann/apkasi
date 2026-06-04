<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ================================================
        // 1. CREATE ALL PERMISSIONS
        // ================================================

        // --- Navigation / Page Access ---
        $navPermissions = [
            'view_dashboard',
            'view_data_master',
            'view_resources',
            'view_help',
        ];

        // --- Granular User Management ---
        $userPermissions = [
            'user.show',
            'user.create',
            'user.edit',
            'user.delete',
            'user.massdelete',
            'user.ban',
        ];

        // --- Granular Role Management ---
        $rolePermissions = [
            'role.show',
            'role.create',
            'role.edit',
            'role.delete',
            'role.massdelete',
        ];

        // --- Granular Data Master: Hotel ---
        $hotelPermissions = [
            'hotel.show',
            'hotel.create',
            'hotel.edit',
            'hotel.delete',
        ];

        // --- Granular Data Master: PIC ---
        $picPermissions = [
            'pic.show',
            'pic.create',
            'pic.edit',
            'pic.delete',
        ];

        // --- Granular Data Master: Destinasi Wisata ---
        $destinasiPermissions = [
            'destinasi.show',
            'destinasi.create',
            'destinasi.edit',
            'destinasi.delete',
        ];

        // Create all permissions
        $allPermissions = array_merge(
            $navPermissions, $userPermissions, $rolePermissions,
            $hotelPermissions, $picPermissions, $destinasiPermissions
        );

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ================================================
        // 2. CREATE ROLES (if they don't exist)
        // ================================================
        $roleSuperadmin = Role::firstOrCreate(['name' => 'Superadmin']);
        $roleAdmin      = Role::firstOrCreate(['name' => 'admin']);

        // ================================================
        // 3. ASSIGN PERMISSIONS TO ROLES
        // ================================================

        // SUPERADMIN — gets everything implicitly via Gate::before in AppServiceProvider
        // But we still assign explicitly for completeness
        $roleSuperadmin->syncPermissions(Permission::all());

        // ADMIN — All except Resources (User/Role Management); bisa kelola Data Master
        $adminPermissions = array_merge([
            'view_dashboard',
            'view_data_master',
            'view_help',
        ], $hotelPermissions, $picPermissions, $destinasiPermissions);
        $roleAdmin->syncPermissions($adminPermissions);
    }
}
