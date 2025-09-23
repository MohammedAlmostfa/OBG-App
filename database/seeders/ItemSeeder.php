<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File as HttpFile;
use App\Models\Item;
use App\Models\Photo;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // مجلد الصور المصدر
        $imageDir = base_path('database/seeders/images');
        $images   = File::files($imageDir);

        if (empty($images)) {
            $this->command->warn('⚠️ لا توجد صور في database/seeders/images/');
            return;
        }

        // أنشئ 10 منتجات
        $items = Item::factory(10)->create();

        foreach ($items as $index => $item) {
            $imageFile = $images[$index % count($images)];

            // حول الصورة لكائن File ليعمل Laravel putFile
            $file = new HttpFile($imageFile->getRealPath());

            // انسخ الصورة لمجلد storage/app/public/items/photos
            $storedPath = Storage::disk('public')->putFile('items/photos', $file);

            // خزّن المسار بالجدول (مسار نسبي داخل storage)
            Photo::create([
                'photoable_id'   => $item->id,
                'photoable_type' => Item::class,
                'url'            => $storedPath, // ex: items/photos/abc123.jpg
            ]);
        }

        $this->command->info('✅ تم نسخ وربط الصور بالمنتجات');
    }
}
