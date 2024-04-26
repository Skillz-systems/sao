<?php

namespace Database\Factories;

use App\Models\MeshgridConnections;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */


class MeshgridConnectionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = MeshgridConnections::class;
    public function definition(): array
    {
        return [
            "order_description" => $this->faker->text($maxNbChars = 40),
            "meshgrid_id" => 1,
            "client_id" => 1,
            "address_id" => 1,
            "amount" => 900,
            "actual_amount" => 1000,
            "discount" => 10,
            'connection_number' => 11234003,
            'status' => 1,
            'payment_status' => 0,
        ];
    }
}
