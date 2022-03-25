<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategoryGroupsSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::Now();

        DB::table('category_groups')->insert([
            [
                'id' => 1,
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Cookware, Dining, Bath, Home Decor and more',
                'icon' => 'fa-shower',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'id' => 2,
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Mobile, Computer, Tablet, Camera etc',
                'icon' => 'fa-plug',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'id' => 3,
                'name' => 'Kids and Toy',
                'slug' => 'kids-toy',
                'description' => 'Toys, Footwear etc',
                'icon' => 'fa-gamepad',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'id' => 4,
                'name' => 'Clothing and Shoes',
                'slug' => 'clothing-shoes',
                'description' => 'Shoes, Clothing, Life style items',
                'icon' => 'fa-tshirt',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'id' => 5,
                'name' => 'Beauty and Health',
                'slug' => 'beauty-health',
                'description' => 'Cosmetics, Foods and more.',
                'icon' => 'fa-hot-tub',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'id' => 6,
                'name' => 'Sports',
                'slug' => 'sports',
                'description' => 'Cycle, Tennis, Boxing, Cricket and more.',
                'icon' => 'fa-skiing',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'id' => 7,
                'name' => 'Jewelry',
                'slug' => 'jewelry',
                'description' => 'Necklances, Rings, Pendants and more.',
                'icon' => 'fa-gem',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'id' => 8,
                'name' => 'Pets',
                'slug' => 'pets',
                'description' => 'Pet foods and supplies.',
                'icon' => 'fa-dog',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'id' => 9,
                'name' => 'Hobbies & DIY',
                'slug' => 'hobbies-diy',
                'description' => 'Craft Sewing, Supplies and more.',
                'icon' => 'fa-paint-brush',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        if (File::isDirectory($this->demo_dir)) {
            $category_groups = DB::table('category_groups')->pluck('id')->toArray();
            $data = [];

            foreach ($category_groups as $grp) {
                $img = $this->demo_dir."/categories/{$grp}.png";
                if (! file_exists($img)) {
                    continue;
                }

                $name = "category_{$grp}.png";
                $targetFile = $this->dir.DIRECTORY_SEPARATOR.$name;

                if ($this->disk->put($targetFile, file_get_contents($img))) {
                    $data[] = [
                        'name' => $name,
                        'path' => $targetFile,
                        'extension' => 'png',
                        'featured' => 0,
                        'type' => 'background',
                        'imageable_id' => $grp,
                        'imageable_type' => \App\Models\CategoryGroup::class,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            DB::table('images')->insert($data);
        }
    }
}
