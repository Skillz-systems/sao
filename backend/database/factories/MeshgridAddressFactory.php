<?php

namespace Database\Factories;

use App\Models\MeshgridAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MeshgridAddress>
 */
class MeshgridAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = MeshgridAddress::class;
    public function definition(): array
    {
        return [
            "address_description" => $this->faker->text($maxNbChars = 40),
            "states_id" => "78521",
            "lgas_id" => "0000B",
            "projects_id" => 1,
            "client_id" => 1,
            "log" => "ueu38j383",
            "lat" => "337uejnehh",
            "status" => 1,
        ];
    }
}
