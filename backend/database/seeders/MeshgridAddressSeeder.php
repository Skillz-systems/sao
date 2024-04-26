<?php

namespace Database\Seeders;

use App\Models\MeshgridAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeshgridAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MeshgridAddress::factory()->count(1)->create();
    }
}
