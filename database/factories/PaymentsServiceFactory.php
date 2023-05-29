<?php

namespace Database\Factories;

use App\Models\Fees;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PaymentsServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_id' => $this->faker->numberBetween(1, 100),
            'service_id' => $this->faker->numberBetween(1, 20),
            'fee' => $this->faker->randomFloat(2, 100, 10000),
        ];
    }
}