<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'System',
            'phone_number' => '123456789',
            'password' => Hash::make('password'),
        ]);

        $role = Role::where('name', 'admin')->first();
        $admin->assignRole($role);
    }
}
