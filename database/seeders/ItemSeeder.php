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

        $imageDir = base_path('database/seeders/images');
        $images   = File::files($imageDir);

        if (empty($images)) {
            $this->command->warn('⚠️ لا توجد صور في database/seeders/images/');
            return;
        }


        $items = Item::factory(100)->create();

        foreach ($items as $index => $item) {
            $imageFile = $images[$index % count($images)];

            $file = new HttpFile($imageFile->getRealPath());


            $storedPath = Storage::disk('public')->putFile('items/photos', $file);

            Photo::create([
                'photoable_id'   => $item->id,
                'photoable_type' => Item::class,
                'url'            => $storedPath, // ex: items/photos/abc123.jpg
            ]);
        }

        $this->command->info('✅ تم نسخ وربط الصور بالمنتجات');
    }
}
