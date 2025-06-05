<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['en' => 'Gaming', 'ar' => 'ألعاب الفيديو'],
            ['en' => 'Fashion', 'ar' => 'الموضة'],
            ['en' => 'Hobbies', 'ar' => 'الهوايات'],
            ['en' => 'Electronics', 'ar' => 'الإلكترونيات'],
            ['en' => 'Vehicle', 'ar' => 'المركبات'],
            ['en' => 'Real Estate', 'ar' => 'العقارات'],
            ['en' => 'Home & Furniture', 'ar' => 'المنزل'],
            ['en' => 'Beauty', 'ar' => 'الجمال'],
            ['en' => 'Health', 'ar' => 'الصحة'],
            ['en' => 'Kids & Baby Items', 'ar' => 'الأطفال'],
            ['en' => 'Furniture', 'ar' => 'الأثاث'],

        ];


        foreach ($categories as $Category) {
            Category::create([
                'name' => json_encode($Category),
            ]);
        }
    }
}
