<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Sequence;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        Item::factory()->count(6)->state(new Sequence(
            ['item' => 'Inverter'],
            ['item' => 'Solar Panel'],
            ['item' => 'Battery'],
            ['item' => 'Mesh Grid'],
            ['item' => 'Accessories'],
            ['item' => 'Pod'],
        ))->create();
    }
}
