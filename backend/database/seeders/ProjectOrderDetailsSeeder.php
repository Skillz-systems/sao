<?php

namespace Database\Seeders;

use App\Models\ProjectOrderDetails;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectOrderDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ProjectOrderDetails::factory(1)->create();
    }
}
