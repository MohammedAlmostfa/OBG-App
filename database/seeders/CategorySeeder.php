<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            ['en' => 'Electronics', 'ar' => 'الإلكترونيات'],
            ['en' => 'Vehicle', 'ar' => 'المركبات'],
            ['en' => 'Real Estate', 'ar' => 'العقارات'],
            ['en' => 'Beauty', 'ar' => 'الجمال'],
            ['en' => 'Kids & Baby Items', 'ar' => 'الأطفال'],
            ['en' => 'Furniture', 'ar' => 'الأثاث'],
                       ['en' => 'another', 'ar' => 'اخر'],
        ];

        foreach ($categories as $catData) {
            $category = Category::create([
                'name' => $catData, // عمود name لازم يكون json
            ]);

            $slug = !empty($catData['en']) ? Str::slug($catData['en'], '_') : "00";
            $fileName = "{$slug}.svg";

            // مسار الصورة في public
            $sourcePath = public_path("categories/{$fileName}");
            $targetPath = "categories/{$fileName}";

            // لو الصورة موجودة انسخها لـ storage/app/public
            if (file_exists($sourcePath)) {
                Storage::disk('public')->put(
                    $targetPath,
                    file_get_contents($sourcePath)
                );
            }

            // اربط الصورة مع الكاتيجوري
            $category->photo()->create([
                'url' => $targetPath
            ]);
        }
    }
}
