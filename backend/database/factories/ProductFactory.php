<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            "productname" => $this->faker->name(),
            "numberofpanels" => 1,
            "numberofbatteries" => 1,
            "numberofinverters" => 1,
            "description" => "just a product",
            "inverter_type" => 1,
            "panel_type" => 2,
            "product_type" => "meshgrid",
            "batteries_type" => 3,
        ];
    }
}
