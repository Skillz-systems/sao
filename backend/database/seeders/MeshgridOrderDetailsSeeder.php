<?php

namespace Database\Seeders;

use App\Models\MeshgridOrderDetails;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeshgridOrderDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MeshgridOrderDetails::factory(1)->create();
    }
}
