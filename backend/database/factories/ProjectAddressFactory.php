<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectAddress>
 */
class ProjectAddressFactory extends Factory
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
            "projects_id"=>1,
            "client_id"=>1,
            "log"=> $this->faker->latitude(),
            "lat"=> $this->faker->longitude(),
            "address_description"=> $this->faker->text(),
            "states_id"=> 78521,
            "lgas_id"=> "0000B",
            "from_where" => "meshgrid",
            "status" => 1,
        ];
    }
}
