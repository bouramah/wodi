<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agent = User::create([
            'first_name' => 'Agent',
            'last_name' => 'Test',
            'phone_number' => '111111111',
            'password' => Hash::make('password'),
        ]);

        $agent->assignRole('agent');
    }
}
