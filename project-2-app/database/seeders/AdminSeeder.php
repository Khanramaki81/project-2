<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' =>'zahra',
            'email' =>'zahra@gmail.com',
            'password' => bcrypt('zahra123'),
            'phone' =>'0123456789',
            ]);
        $user->assignRole('admin');
    }
}
