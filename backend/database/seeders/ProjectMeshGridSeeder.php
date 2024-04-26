<?php

namespace Database\Seeders;

use App\Models\ProjectMeshGrid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectMeshGridSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ProjectMeshGrid::factory(1)->create();
    }
}
