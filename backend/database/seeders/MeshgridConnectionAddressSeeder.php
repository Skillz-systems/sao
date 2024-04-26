<?php

namespace Database\Seeders;

use App\Models\MeshgridConnectionAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeshgridConnectionAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        MeshgridConnectionAddress::factory(1)->create();
    }
}
