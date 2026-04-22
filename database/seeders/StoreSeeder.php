<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreBallance;
use App\Models\StoreBallanceHistory;
use App\Models\Withdrawal;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::factory()->count(10)->create()->each(function ($store) {
            $StoreBallance = StoreBallance::factory()->create(['store_id' => $store->id]);
            StoreBallanceHistory::factory()->create([
                'store_ballance_id' => $StoreBallance->id,
                'amount' => $StoreBallance->balance
            ]);
            Withdrawal::factory()->count(1)->create([
                'store_ballance_id' => $StoreBallance->id
            ]);
        });
    }
}
