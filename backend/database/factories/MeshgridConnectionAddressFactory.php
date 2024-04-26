<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MeshgridConnectionAddress>
 */
class MeshgridConnectionAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'meshgrid_id' => 1,
            "client_id" => 1,
            "log" => "ihiuh3u",
            "lat" => "7797898",
            "address_description" =>  $this->faker->name(),
            "states_id" => "78521",
            "lgas_id" => "0000B",
            "status" => 1,
        ];
    }
}
