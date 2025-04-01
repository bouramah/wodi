<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'agent', 'guard_name' => 'web'],
            ['name' => 'client', 'guard_name' => 'web'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
