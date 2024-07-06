<?php

namespace Database\Seeders;

use App\Models\CommissionTier;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommissionTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CommissionTier::create(['min_amount' => 0, 'max_amount' => 5, 'percentage' => 20]);
        CommissionTier::create(['min_amount' => 5, 'max_amount' => 45, 'percentage' => 15]);
        CommissionTier::create(['min_amount' => 45, 'max_amount' => 75, 'percentage' => 11]);
        CommissionTier::create(['min_amount' => 75, 'max_amount' => 140, 'percentage' => 9]);
        CommissionTier::create(['min_amount' => 140, 'max_amount' => null, 'percentage' => 7]);
    }
}
