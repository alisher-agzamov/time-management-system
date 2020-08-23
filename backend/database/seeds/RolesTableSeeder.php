<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRole       = Role::create(['name' => 'user']);
        $managerRole    = Role::create(['name' => 'manager']);
        $adminRole      = Role::create(['name' => 'admin']);

        $tasksPermission = Permission::create(['name' => 'tasks']);
        $usersPermission = Permission::create(['name' => 'users']);

        $userRole->givePermissionTo($tasksPermission);
        $managerRole->givePermissionTo($usersPermission);

        // Both permissions
        $adminRole->syncPermissions([$tasksPermission, $usersPermission]);
    }
}
