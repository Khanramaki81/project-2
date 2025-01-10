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
            'show_all_users' => 'نمایش همه کاربران به ادمین',
            'delete_avatar' => 'حذف آواتار کاربر',
            'store_avatar' => 'ذخیره آواتار کاربر',
            'create_user' => 'ایجاد کاربر',
            'edit_user_status' => 'تعیین وضعیت کاربر',
            'show_user' => 'نمایش اطلاعات یک کاربر',
            'delete_user' => 'حذف یک کاربر',
            'edit_user' => 'ویرایش اطلاعات یک کاربر',
            'show_roles' => 'نمایش نقش های موجود',
            'create_role' => 'ایجاد یک نقش',
            'show_user_roles' => 'نمایش نقش های یک کاربر',
            'delete_user_roles' => 'حذف نقش های یک کاربر',
            'edit_role' => 'ویرایش یک نقش به همراه مجوز های آن',
            'show_permissions' => 'نمایش مجوز های موجود',
            'assign_user_role' => 'اختصاص نقش به کاربر',
            'delete_role' => 'حذف نقش'
        ];
        $permissions = collect($permissions)->map(function ($permission,$name) {
            return ['description' => $permission,'name' => $name, 'guard_name' => 'sanctum'];
        });
        Permission::insert($permissions->toArray());
        $role = Role::create([
            'name' => 'admin',
            'description'=>'می تواند پنل ادمین را مدیریت کند.',
            ]);
        $role1 = Role::create([
            'name' => 'author',
            'description'=>'می تواند پنل ادمین را مدیریت کند.',
        ]);
        $role->givePermissionTo(Permission::all());

//        $role = Role::create(['name' => 'user']);
//        $role->givePermissionTo(['edit_profile']);

    }
}
