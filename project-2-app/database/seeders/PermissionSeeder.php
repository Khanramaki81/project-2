<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = ['create_post','show_post', 'edit_post','delete_post','edit_user','edit_profile'];
//        $permissions = collect($permissions)->map(function($permission){
//            return ['name' => $permission, 'guard_name' => 'web'];
//        });
//        Permission::insert($permissions->toArray());

        $permissions = collect($permissions)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web'];
        });

        Permission::insert($permissions->toArray());

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
//        $role = Role::create(['name' => 'user']);
//        $role->givePermissionTo(['edit_profile']);

    }
}
