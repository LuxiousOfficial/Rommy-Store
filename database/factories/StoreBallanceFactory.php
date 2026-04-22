<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\StoreBallance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StoreBallance>
 */
class StoreBallanceFactory extends Factory
{
    protected $model = StoreBallance::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'balance' => $this->faker->randomFloat(2, 0, 1000000)
        ];
    }
}
