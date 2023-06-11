<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Fecalysis;
use App\Models\Fees;
use App\Models\Payment;
use App\Models\PaymentsService;
use App\Models\Urinalysis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UrinalysisRecord>
 */
class UrinalysisRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $clientIds = Client::pluck('infirmary_id')->toArray();
        $record = Urinalysis::factory()->create();
        $client = $this->faker->randomElement($clientIds);
        $services = Fees::pluck('service_id')->toArray();
        $fees = Fees::pluck('amount')->toArray();
        $payment = Payment::factory()->create(
            [
                'payor_name' => $this->faker->name,
                'infirmary_id' => $client,
                'collector_id' => $this->faker->numberBetween(2,4),
                'total_amount' => $this->faker->randomElement($fees),
            ]
        );
        $payment = PaymentsService::factory()->create(
            [
                'payment_id' => $payment->or_no,
                'service_id' => $this->faker->randomElement($services),
                'fee' => $this->faker->randomElement($fees),
            ]
        );
        return [
            'infirmary_id' => $client,
            'urinalysis_id' => $record->id,
            'rqst_physician' => $this->faker->numberBetween(2,4),
            'medical_technologist' => $this->faker->numberBetween(2,4),
            'pathologist' => $this->faker->numberBetween(2,4),
            'or_no' => $payment->payment_id,
            'ward' => $this->faker->randomElement(['opd','er','male-ward','female-ward','pedia-ward']),
            'status' => $this->faker->randomElement(['pending','done','released']),
        ];
    }
}
