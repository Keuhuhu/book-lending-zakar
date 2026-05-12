<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@booklending.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $users = [
            ['name' => 'Budi Santoso',   'email' => 'budi@example.com'],
            ['name' => 'Siti Rahayu',    'email' => 'siti@example.com'],
            ['name' => 'Ahmad Fauzi',    'email' => 'ahmad@example.com'],
            ['name' => 'Dewi Lestari',   'email' => 'dewi@example.com'],
            ['name' => 'Rizky Pratama',  'email' => 'rizky@example.com'],
        ];

        foreach ($users as $user) {
            User::create([
                'name'     => $user['name'],
                'email'    => $user['email'],
                'password' => Hash::make('password'),
                'role'     => 'user',
            ]);
        }
    }
}