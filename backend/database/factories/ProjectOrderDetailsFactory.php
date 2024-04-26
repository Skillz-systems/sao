<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectOrderDetails>
 */
class ProjectOrderDetailsFactory extends Factory
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
            "product_type" => "panel",
            "product_id" => 3,
            "order_id" => 1,
            "project_id" => 2,
            "client_id" => 1,
            "status" => 1,
        ];
    }
}
