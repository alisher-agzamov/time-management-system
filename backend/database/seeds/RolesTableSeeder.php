<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Traits\HasPermissions;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!$userRole = Role::where(['name' => 'user'])->first()) {
            $userRole = Role::create(['name' => 'user']);
        }

        if(!$managerRole = Role::where(['name' => 'manager'])->first()) {
            $managerRole = Role::create(['name' => 'manager']);
        }

        if(!$adminRole = Role::where(['name' => 'admin'])->first()) {
            $adminRole = Role::create(['name' => 'admin']);
        }

        if(!$tasksPermission = Permission::where(['name' => 'tasks'])->first()) {
            $tasksPermission = Permission::create(['name' => 'tasks']);
        }

        if(!$usersPermission = Permission::where(['name' => 'users'])->first()) {
            $usersPermission = Permission::create(['name' => 'users']);
        }

        if(!$userRole->hasPermissionTo($tasksPermission)) {
            $userRole->givePermissionTo($tasksPermission);
        }

        if(!$managerRole->hasPermissionTo($usersPermission)) {
            $managerRole->givePermissionTo($usersPermission);
        }

        // Both permissions
        $adminRole->syncPermissions([$tasksPermission, $usersPermission]);
    }
}
