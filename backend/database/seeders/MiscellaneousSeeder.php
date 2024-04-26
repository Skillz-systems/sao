<?php

namespace Database\Seeders;

use App\Models\Miscellaneous;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MiscellaneousSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Miscellaneous::factory()->create();
    }
}
