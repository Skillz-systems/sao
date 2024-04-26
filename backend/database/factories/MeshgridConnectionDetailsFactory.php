<?php

namespace Database\Factories;

use App\Models\MeshgridConnectionDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MeshgridConnectionDetails>
 */
class MeshgridConnectionDetailsFactory extends Factory
{ 
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = MeshgridConnectionDetails::class;
    public function definition(): array
    {
        return [
            "product_type" => "pod",
            "product_id" => 4,
            "order_id" => 1,
            "project_id" => 1,
            "client_id" => 1,
            "status" => 1,
        ];
    }
}
