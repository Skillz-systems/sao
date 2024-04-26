<?php

namespace Database\Factories;

use App\Models\Subitem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subitem>
 */
class SubitemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Subitem::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'userid' => 1,
            'status' => 0,
        ];
    }
}
