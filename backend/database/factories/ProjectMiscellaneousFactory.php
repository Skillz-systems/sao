<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectMiscellaneous>
 */
class ProjectMiscellaneousFactory extends Factory
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
            "project_id" => 2,
            "order_id" => 1,
            "miscellaneous_id" => 1,
            "amount" => 200,
            "status" => 1,
        ];
    }
}
