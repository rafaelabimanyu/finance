<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Owner
        User::updateOrCreate(
            ['email' => 'owner@jj-group.id'],
            [
                'name' => 'Ardy (Owner)',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ]
        );

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@jj-group.id'],
            [
                'name' => 'Abi (Admin)',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }
}
