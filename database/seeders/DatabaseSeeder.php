<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::create([
            'First_name' => "Super",
            'Last_name' => "Admin",
            'email' => "superadmin@gmail.com",
            'phone_number' => "09999999999",
            'password' => Hash::make("9999999999"),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);
    }
}
