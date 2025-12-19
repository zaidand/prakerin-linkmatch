<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();

        User::firstOrCreate(
            ['email' => 'admin@prakerin.test'],
            [
                'name' => 'Super Admin',
                'role_id' => $adminRole->id,
                'password' => Hash::make('password'), // Ganti setelah login pertama
                'status' => 'active',
            ]
        );
    }
}
