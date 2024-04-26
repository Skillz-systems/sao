<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            MeshgridConnectionDetailsSeeder::class,
            ProductSeeder::class,
            ClientSeeder::class,
            ItemSeeder::class,
            UserSeeder::class,
            StockadditionSeeder::class,
            ProjectSeeder::class,
            SubitemSeeder::class,
            MeshgridAddressSeeder::class,
            MeshgridConnectionSeeder::class,
            MeshgridConnectionAddressSeeder::class,
            MeshgridOrderDetailsSeeder::class,
            MiscellaneousSeeder::class,
            PaymentSeeder::class,
            ProjectAddressSeeder::class,
            ProjectMeshGridSeeder::class,
            ProjectMiscellaneousSeeder::class,
            ProjectOrderDetailsSeeder::class,
            ProjectOrderSeeder::class,
            StateCountrySeeder::class,
        ]);
    }
}
