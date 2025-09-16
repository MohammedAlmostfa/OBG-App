<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // التصنيفات الفرعية مرتبة حسب التصنيفات الأساسية
        $subCategoriesByCategory = [
            'Gaming' => [
                ['en' => 'Consoles', 'ar' => 'أجهزة الألعاب'],
                ['en' => 'Accessories', 'ar' => 'إكسسوارات الألعاب'],
                ['en' => 'PCs', 'ar' => 'أجهزة الكمبيوتر'],
            ],
            'Fashion' => [
                ['en' => 'Clothing', 'ar' => 'الملابس'],
                ['en' => 'Shoes', 'ar' => 'الأحذية'],
                ['en' => 'Jewelry', 'ar' => 'المجوهرات'],
            ],
            'Hobbies' => [
                ['en' => 'Art Supplies', 'ar' => 'مستلزمات الفنون'],
                ['en' => 'Musical Instruments', 'ar' => 'الآلات الموسيقية'],
                ['en' => 'DIY Kits', 'ar' => 'أطقم الأعمال اليدوية'],
            ],
            'Electronics' => [
                ['en' => 'Phones', 'ar' => 'الهواتف'],
                ['en' => 'Tablets', 'ar' => 'الأجهزة اللوحية'],
                ['en' => 'Laptops', 'ar' => 'الحواسيب المحمولة'],
            ],
            'Vehicle' => [
                ['en' => 'Cars for Sale', 'ar' => 'سيارات للبيع'],
                ['en' => 'Cars for Rent', 'ar' => 'سيارات للإيجار'],
                ['en' => 'Motorcycles', 'ar' => 'الدراجات النارية'],
            ],
            'Real Estate' => [
                ['en' => 'Apartments', 'ar' => 'الشقق'],
                ['en' => 'Villas', 'ar' => 'الفلل'],
                ['en' => 'Commercial Spaces', 'ar' => 'المساحات التجارية'],
            ],
            'Beauty' => [
                ['en' => 'Skincare', 'ar' => 'العناية بالبشرة'],
                ['en' => 'Makeup', 'ar' => 'مستحضرات التجميل'],
                ['en' => 'Fitness Equipment', 'ar' => 'معدات اللياقة البدنية'],
            ],
            'Kids & Baby Items' => [
                ['en' => 'Toys', 'ar' => 'الألعاب'],
                ['en' => 'Strollers', 'ar' => 'عربات الأطفال'],
            ],
        ];


        $categories = Category::all();

        foreach ($categories as $category) {
            $categoryName = $category->name['en']; // مباشرة لأن الـ cast array

            if (isset($subCategoriesByCategory[$categoryName])) {
                foreach ($subCategoriesByCategory[$categoryName] as $subCategory) {
                    SubCategory::create([
                        'category_id' => $category->id,
                        'name' => $subCategory, // array مباشرة
                    ]);
                }
            }
        }
    }
}
