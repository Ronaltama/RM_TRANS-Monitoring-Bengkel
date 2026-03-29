<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'name'     => 'admin',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ],
            [
                'username' => 'superadmin',
                'name'     => 'superadmin',
                'password' => Hash::make('password123'),
                'role'     => 'superadmin',
            ],
        ];

        foreach ($users as $data) {
            User::create($data);
        }
    }
}
