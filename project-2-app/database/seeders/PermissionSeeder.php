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

//        $permissions = ['create_post','show_post', 'edit_post','delete_post','delete_user','edit_profile'];
//        $description = ['ایجاد کردن پست.', 'نمایش دادن پست.', 'ویرایش کردن پست.','حذف کردن پست.', 'حذف کاربر', 'ویرایش پروفایل'];
//        $permissions = collect($permissions)->map(function($permission){
//            return ['name' => $permission, 'guard_name' => 'web'];
//        });
//        Permission::insert($permissions->toArray());

//        $permissions = collect($permissions)->map(function ($permission) {
//            return ['name' => $permission, 'guard_name' => 'web'];
//        });

//        Permission::insert($permissions->toArray());
        $permissions = [
            'create_post' => 'ایجاد کردن پست.',
            'show_post' => 'نمایش دادن پست.',
            'edit_post' => 'ویرایش کردن پست.',
            'delete_post' => 'حذف کردن پست.',
            'delete_user' => 'حذف کاربر',
            'edit_profile' => 'ویرایش پروفایل'
        ];
        $permissions = collect($permissions)->map(function ($permission,$name) {
            return ['description' => $permission,'name' => $name, 'guard_name' => 'web'];
        });
        Permission::insert($permissions->toArray());
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

//        $role = Role::create(['name' => 'user']);
//        $role->givePermissionTo(['edit_profile']);

    }
}
