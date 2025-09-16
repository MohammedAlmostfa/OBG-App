<?php

namespace Database\Seeders;

use App\Models\SubCategory;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => "mohammedalmostyfa336@gamil.com",
            'password' => bcrypt('P@ssw0rd123')
        ]);
        $this->call([
            CountrySeeder::class,
            ProvinceSeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            ItemSeeder::class,

        ]);
    }
}
