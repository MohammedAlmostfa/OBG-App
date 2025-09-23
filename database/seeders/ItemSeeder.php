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
        // مجلد الصور بتاريخ اليوم
        $imageDir = public_path('storage/items/photos/2025-09-22');
        $images   = File::files($imageDir);

        // أنشئ 10 منتجات
        $items = Item::factory(10)->create();

        foreach ($items as $index => $item) {
            // اختار صورة (بالتناوب إذا الصور أقل من عدد المنتجات)
            $imageFile   = $images[$index % count($images)];
            $relativePath = 'items/photos/2025-09-22/' . $imageFile->getFilename();

            // خزّن الصورة بالجدول
            Photo::create([
                'photoable_id'   => $item->id,
                'photoable_type' => Item::class,
                'url'            => $relativePath, // مثال: items/photos/2025-09-22/img1.jpg
            ]);
        }
    }
}
