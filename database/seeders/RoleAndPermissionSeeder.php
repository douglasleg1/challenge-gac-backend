<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guardName = 'web';

        // Permissões
        $permissions = [
            // Users
            'read-users', 'create-users', 'edit-users', 'update-password-users', 'delete-users',

            // Roles
            'read-roles', 'create-roles', 'edit-roles', 'delete-roles',

            // Permissions
            'read-permissions', 'create-permissions', 'edit-permissions', 'delete-permissions',

            // Logs
            'read-logs', 'create-logs', 'edit-logs', 'delete-logs',

            // Wallet
            'create-transfer', 'create-revert', 'list-transfer', 'create-deposit'

        ];

        // Criação das permissões
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => $guardName], ['name' => $permission, 'guard_name' => $guardName]);
        }

        // Criação das roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => $guardName], ['name' => 'Admin', 'guard_name' => $guardName]);
        $userRole = Role::firstOrCreate(['name' => 'User', 'guard_name' => $guardName], ['name' => 'User', 'guard_name' => $guardName]);

        // Atribuir todas as permissões para a role Admin
        $adminRole->syncPermissions(Permission::where('guard_name', $guardName)->get());

        $userRole->syncPermissions([
                'read-users', 'create-users', 'edit-users', 'update-password-users', 'delete-users',
                'create-transfer', 'create-revert', 'list-transfer', 'create-deposit',
                'read-logs', 'create-logs', 'edit-logs', 'delete-logs',
        ]);
    }
}
