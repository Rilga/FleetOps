<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Membuat User Admin (untuk login manual)
        User::create([
            'name' => 'Fleet Manager',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('admin12345'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Chief Engineer',
            'email' => 'chief@gmail.com',
            'role' => 'chief',
            'password' => Hash::make('chief12345'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => '2nd Engineer',
            'email' => '2nd@gmail.com',
            'role' => 'user',
            'password' => Hash::make('user12345'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => '3rd Engineer',
            'email' => '3rd@gmail.com',
            'role' => 'user',
            'password' => Hash::make('user12345'),
            'email_verified_at' => now(),
        ]);
    }
}
