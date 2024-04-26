<?php

namespace Database\Seeders;

use App\Models\ProjectOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ProjectOrder::factory(1)->create();
    }
}
