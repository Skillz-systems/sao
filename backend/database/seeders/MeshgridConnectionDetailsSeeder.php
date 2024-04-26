<?php

namespace Database\Seeders;

use App\Models\MeshgridConnectionDetails;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeshgridConnectionDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MeshgridConnectionDetails::factory(1)->create();
    }
}
