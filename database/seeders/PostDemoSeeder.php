<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PostDemoSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images_data = [];
        $now = Carbon::Now();

        // add few more product into the trending cats
        $trending = DB::table('options')->where('option_name', 'trending_categories')->select('option_value')->first()->option_value;
        $trending = is_serialized($trending) ? unserialize($trending) : $trending;
        $products = DB::table('products')->pluck('id')->toArray();

        $data = [];
        foreach ($trending as $cat) {
            for ($i = 0; $i < rand(8, 16); $i++) {
                $data[] = [
                    'category_id' => $cat,
                    'product_id' => $products[array_rand($products)],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('category_product')->insert($data);

        // Seed few product for featured brands
        $featured_brands = DB::table('options')->where('option_name', 'featured_brands')
            ->select('option_value')->first()->option_value;

        $featured_brands = is_serialized($featured_brands) ? unserialize($featured_brands) : $featured_brands;

        $i = 1;
        foreach ($featured_brands as $brand) {
            DB::table('products')->whereIn('id', range($i, $i + 4))->update(['manufacturer_id' => $brand]);

            $i = $i + 5;
        }

        // All images
        if (File::isDirectory($this->demo_dir)) {
            // Seed featured_brands feature img
            $files = glob($this->demo_dir.'/brands/feature/*.{jpg,png,jpeg}', GLOB_BRACE);
            $images = $this->get_random_element($files, count($featured_brands));

            foreach ($featured_brands as $key => $brand) {
                $file = $images[$key];

                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $name = Str::random(10).'.'.$ext;
                $targetFile = $this->dir.DIRECTORY_SEPARATOR.$name;

                if ($this->disk->put($targetFile, file_get_contents($file))) {
                    $images_data[] = [
                        'name' => $name,
                        'path' => $targetFile,
                        'extension' => $ext,
                        'size' => 0,
                        'type' => 'feature',
                        'imageable_id' => $brand,
                        'imageable_type' => \App\Models\Manufacturer::class,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            // Seed featured_category feature img
            $featured_category = DB::table('categories')->where('featured', 1)->pluck('id')->toArray();
            $files = glob($this->demo_dir.'/categories/feature/*.{jpg,png,jpeg}', GLOB_BRACE);
            $images = $this->get_random_element($files, count($featured_category));

            foreach ($featured_category as $key => $category) {
                $file = $images[$key];

                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $name = Str::random(10).'.'.$ext;
                $targetFile = $this->dir.DIRECTORY_SEPARATOR.$name;

                if ($this->disk->put($targetFile, file_get_contents($file))) {
                    $images_data[] = [
                        'name' => $name,
                        'path' => $targetFile,
                        'extension' => $ext,
                        'size' => 0,
                        'type' => 'feature',
                        'imageable_id' => $category,
                        'imageable_type' => \App\Models\Category::class,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            foreach ($trending as $key => $category) {
                $file = $images[$key];

                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $name = Str::random(10).'.'.$ext;
                $targetFile = $this->dir.DIRECTORY_SEPARATOR.$name;

                if ($this->disk->put($targetFile, file_get_contents($file))) {
                    $images_data[] = [
                        'name' => $name,
                        'path' => $targetFile,
                        'extension' => $ext,
                        'size' => 0,
                        'type' => 'feature',
                        'imageable_id' => $category,
                        'imageable_type' => \App\Models\Category::class,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            // Brand logo seeder
            $manufacturers = DB::table('manufacturers')->pluck('id')->toArray();
            $logos = glob($this->demo_dir.'/logos/*.{jpg,png,jpeg}', GLOB_BRACE);

            // Brand logo seeder
            foreach ($manufacturers as $manufacturer) {
                $file = $logos[array_rand($logos)];
                $ext = pathinfo($file, PATHINFO_EXTENSION);

                $name = Str::random(10).'.'.$ext;
                $targetFile = $this->dir.DIRECTORY_SEPARATOR.$name;

                if ($this->disk->put($targetFile, file_get_contents($file))) {
                    $images_data[] = [
                        'name' => $name,
                        'path' => $targetFile,
                        'extension' => $ext,
                        'size' => 0,
                        'type' => 'logo',
                        'imageable_id' => $manufacturer,
                        'imageable_type' => \App\Models\Manufacturer::class,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            // Insert all images at once
            DB::table('images')->insert($images_data);
        }
    }
}
