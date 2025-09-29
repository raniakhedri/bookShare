<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@bookshare.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create regular users for testing
        $user1 = User::firstOrCreate(
            ['email' => 'john@example.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'jane@example.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        $this->command->info('Marketplace seeder completed!');
        $this->command->info('Admin user: admin@bookshare.com / password');
        $this->command->info('User 1: john@example.com / password');
        $this->command->info('User 2: jane@example.com / password');
        $this->command->info('Note: No static books created - users will add their own books');
    }
}
