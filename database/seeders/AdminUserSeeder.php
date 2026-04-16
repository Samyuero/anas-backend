<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin1@gmail.com'], // find by email
            [
                'name' => 'System Admin',
                'mobile' => '09926351450',
                'password' => Hash::make('123123123'), // change this!
                'utype' => 'ADM', // make sure your users table has a 'role' column
            ]
        );
    }
}
