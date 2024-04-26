<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
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
            "amount" => 200,
            "actual_amount" => 220,
            "type_of_payment" => "one off",
            "fromwhere" => "meshgrid",
            "order_id" => 0,
            "project_id" => 1,
            "meshgrid_id" =>1,
            "status" => 1,
        ];
    }
}
