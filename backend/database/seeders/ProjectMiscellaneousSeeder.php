<?php

namespace Database\Seeders;

use App\Models\ProjectMiscellaneous;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectMiscellaneousSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ProjectMiscellaneous::factory(1)->create();
    }
}
