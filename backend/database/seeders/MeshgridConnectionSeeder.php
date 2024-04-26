<?php

namespace Database\Seeders;

use App\Models\MeshgridConnections;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeshgridConnectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MeshgridConnections::factory(3)->create();
    }
}
