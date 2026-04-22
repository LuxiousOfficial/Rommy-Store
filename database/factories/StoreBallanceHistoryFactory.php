<?php

namespace Database\Factories;

use App\Models\StoreBallance;
use App\Models\StoreBallanceHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StoreBallanceHistory>
 */
class StoreBallanceHistoryFactory extends Factory
{
    protected $model = StoreBallanceHistory::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_ballance_id' => StoreBallance::factory(),
            'type' => 'initial',
            'reference_id' => null,
            'reference_type' => null,
            'amount' => $this->faker->randomFloat(2, 0, 1000000),
            'remarks' => 'Create new store'
        ];
    }
}
