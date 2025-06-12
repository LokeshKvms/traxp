<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $base = fake()->numberBetween(1, 20) * 500;

        $decimal = fake()->boolean(50) // 50% chance of decimal
            ? fake()->randomElement([0.25, 0.5, 0.75])
            : 0;

        
        return [
            //
            'user_id' => User::inRandomOrder()->value('id'),
            'category_id' => Category::inRandomOrder()->value('id'),
            'type' => fake()->randomElement(['cash_in', 'cash_out']),
            'amount' => round(($base+$decimal),2),
            'desc' => fake()->words(rand(4, 7), true),
            'transaction_date' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
