<?php

namespace Database\Seeders\Admin;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'username' => 'admin'.date('Y'),
                'password' => Hash::make('password'),
                'role' => 1,
            ],
        );

        if ($admin->role !== 1) {
            $admin->forceFill(['role' => 1])->save();
        }
    }
}
