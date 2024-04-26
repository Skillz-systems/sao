<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MeshgridOrderDetails>
 */
class MeshgridOrderDetailsFactory extends Factory
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
            "product_id" => 1,
            "meshgrid_project_link_id" => 1,
            "project_id" => 1,
            "client_id" => 1,
            "product_type" => "meshgrid",
            "status" => 1,
        ];
    }
}
