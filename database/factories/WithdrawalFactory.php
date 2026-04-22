<?php

namespace Database\Factories;

use App\Models\StoreBallance;
use App\Models\Withdrawal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Withdrawal>
 */
class WithdrawalFactory extends Factory
{
    protected $model = Withdrawal::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_ballance_id' => StoreBallance::factory(),
            'amount' => function (array $attributes) {
                $storeBallance = StoreBallance::find($attributes['store_ballance_id']);
                return $this->faker->randomFloat(2, 0, $storeBallance->balance);
            },
            'bank_account_name' => $this->faker->name,
            'bank_account_number' => $this->faker->numerify('##########'),
            'bank_name' => $this->faker->randomElement(['BCA', 'Mandiri', 'BNI', 'BRI']),
            'status' => 'Pending'
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Withdrawal $withdrawal) {

            $withdrawal->storeBallance->storeBallanceHistories()->create([
                'type' => 'Withdraw',
                'reference_id' => $withdrawal->id,
                'reference_type' => Withdrawal::class,
                'amount' => -$withdrawal->amount,
                'remarks' => "Request for Withdrawal to {$withdrawal->bank_name} - {$withdrawal->bank_account_number}",
            ]);

            $withdrawal->storeBallance->storeBallanceHistories()->create([
                'type' => 'Withdraw',
                'reference_id' => $withdrawal->id,
                'reference_type' => Withdrawal::class,
                'amount' => -$withdrawal->amount,
                'remarks' => "Request for Withdrawal to {$withdrawal->bank_name} - {$withdrawal->bank_account_number} has been processed",
            ]);

            $withdrawal->update(['status' => 'approved']);

            $withdrawal->storeBallance->update([
                'balance' => $withdrawal->storeBallance->balance - $withdrawal->amount
            ]);
        });
    }
}
