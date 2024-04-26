<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectMeshGrid>
 */
class ProjectMeshGridFactory extends Factory
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
            "productname" => $this->faker->name(),
            "project_id" => 1,
            "product_id" => 1,
            "maxmum_connection" => 4,
            "mesh_grid_price" => 473,
            "pod_installation_price" => 118.25,
            "deployment_address_id" => 1,
        ];
    }
}
