<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Fees;
use App\Models\Payment;
use App\Models\Services;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $clientIds = Client::pluck('id')->toArray();
        return [
            'or_no' => $this->faker->unique()->numerify('#####'),
            'payor_name' => $this->faker->name(),
            'payor_email' => $this->faker->email(),
            'payor_mobile' => $this->faker->phoneNumber(),
            'client_id' => $this->faker->randomElement($clientIds),
            'collector_id' => $this->faker->numberBetween(1, 4),
            'total_amount' => $this->faker->numberBetween(100, 1000),
            'remarks' => $this->faker->sentence(),
        ];
    }
}
