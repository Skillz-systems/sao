<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\Stockaddition;
class StockadditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Stockaddition::factory()->count(12)->state(new Sequence(
            ['itemid' => 4,"subitemid" => 1],
            ['itemid' => 2,"subitemid" => 2],
            ['itemid' => 3,"subitemid" => 3],
            ['itemid' => 6,"subitemid" => 5],
        ))->create();
    }
}
