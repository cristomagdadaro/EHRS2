<?php

namespace Database\Factories;

use App\Models\AccountRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AccountRole>
 */
class accountRolesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->role(),
        ];
    }
}
