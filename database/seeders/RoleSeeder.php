<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin',               'description' => 'Administrator'],
            ['name' => 'teacher',             'description' => 'Guru Pembimbing'],
            ['name' => 'industry_supervisor', 'description' => 'Pembimbing Lapangan Industri'],
            ['name' => 'student',             'description' => 'Siswa'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
