<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Item;
use App\Models\Photo;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // مجلد الصور
        $imageDir = base_path('database/seeders/images');
        $images   = File::files($imageDir);

        if (empty($images)) {
            $this->command->warn('⚠️ لا توجد صور في database/seeders/images/');
            return;
        }

        // أنشئ 10 منتجات
        $items = Item::factory(10)->create();

        foreach ($items as $index => $item) {
            // اختار صورة بشكل دوري
            $imageFile = $images[$index % count($images)];

            // المسار النسبي للربط مباشرة
            $relativePath = 'items/photos/' . $imageFile->getFilename();

            // خزّن المسار بالجدول
            Photo::create([
                'photoable_id'   => $item->id,
                'photoable_type' => Item::class,
                'url'            => $relativePath, // ex: items/photos/img1.jpg
            ]);
        }

        $this->command->info('✅ تم ربط الصور بالمنتجات');
    }
}
