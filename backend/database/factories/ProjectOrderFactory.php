<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectOrder>
 */
class ProjectOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            "order_number" => $this->faker->numerify(),
            "order_description" => $this->faker->text($maxNbChars = 40),
            "project_id" => 2,
            "client_id" => 1,
            "address_id" => 1,
            "amount" => 100,
            "actual_amount" => 110,
            "status" => 1,
        ];
    }
}
