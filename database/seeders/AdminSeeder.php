<?php

// Seeder untuk membuat 3 user admin default

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $admins = [
            [
                'name' => 'Labib',
                'email' => 'Labib@gmail.com',
                'password' => Hash::make('Labib02'),
            ],
        ];

        foreach ($admins as $admin) {
            User::firstOrCreate(['email' => $admin['email']], $admin);
        }
    }
}
