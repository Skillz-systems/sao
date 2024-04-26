<?php

namespace Database\Seeders;

use App\Models\Subitem;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class SubitemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Subitem::factory()->count(18)->state(new Sequence(
            [
                'itemid' => 1,
                'hasb3' => 0,
            ],
            ['itemid' => 2],
            ['itemid' => 3],
            ['itemid' => 4],
            ['itemid' => 6],
            [
                'itemid' => 1,
                'hasb3' => 1,
            ]
        ))->create();
    }
}
