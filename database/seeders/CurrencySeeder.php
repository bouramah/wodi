<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['name' => 'Dollar américain', 'code' => 'USD', 'symbol' => '$'],
            ['name' => 'Euro', 'code' => 'EUR', 'symbol' => '€'],
            ['name' => 'Dirham des Émirats arabes unis', 'code' => 'AED', 'symbol' => 'د.إ'],
            ['name' => 'Ringgit malaisien', 'code' => 'MYR', 'symbol' => 'RM'],
            ['name' => 'Baht thaïlandais', 'code' => 'THB', 'symbol' => '฿'],
            ['name' => 'Yuan chinois', 'code' => 'CNY', 'symbol' => '¥'],
            ['name' => 'Franc guinéen', 'code' => 'GNF', 'symbol' => 'FG'],
            ['name' => 'Dollar libérien', 'code' => 'LRD', 'symbol' => '$'],
            ['name' => 'Franc CFA', 'code' => 'XOF', 'symbol' => 'CFA'],
            ['name' => 'Leone sierra-léonais', 'code' => 'SLL', 'symbol' => 'Le'],
            ['name' => 'Franc CFA', 'code' => 'XOF', 'symbol' => 'CFA'],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}
