<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            "projectname" => $this->faker->name(),
            "project_selection_mode" => "meshgrid",
            "discount_value" => 10,
            "projecttype" => "Residential",
            "description" => $this->faker->text(),
            "productid" => 1,
            "clientid" => 1,
            "status" => 1,
            "price" => 500,
            "payment_status" => 0,
            "projectcode" => str_shuffle("abcdefghijklmnopqrstuvwxyz"),

        ];
    }
}
