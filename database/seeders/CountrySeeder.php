<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Dubai', 'code' => 'AE'],
            ['name' => 'Malaisie', 'code' => 'MY'],
            ['name' => 'Thaïlande', 'code' => 'TH'],
            ['name' => 'Chine', 'code' => 'CN'],
            ['name' => 'Guinée', 'code' => 'GN'],
            ['name' => 'Liberia', 'code' => 'LR'],
            ['name' => 'Côte d\'Ivoire', 'code' => 'CI'],
            ['name' => 'Sierra Leone', 'code' => 'SL'],
            ['name' => 'Mali', 'code' => 'ML'],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
