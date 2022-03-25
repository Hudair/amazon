<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SlidersSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::Now();

        $slugs = DB::table('categories')->pluck('slug')->toArray();
        DB::table('sliders')->insert([
            [
                'id' => 1,
                'title' => 'Slider title',
                'title_color' => '#333E48',
                'sub_title' => 'Sub title <span>with color</span>',
                'sub_title_color' => '#333E48',
                'description' => 'Description with a text',
                'description_color' => '#868E8E',
                'alt_color' => '#FED700',
                'link' => '/category/'.$slugs[array_rand($slugs)],
                'order' => 1,
                'text_position' => 'left',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'id' => 2,
                'title' => 'Wireless <span>Earbuds</span>',
                'title_color' => '#333E48',
                'sub_title' => 'Slider subtitle with love',
                'sub_title_color' => '#333E48',
                'description' => 'Description with <span>span</span> and color',
                'description_color' => '#868E8E',
                'alt_color' => '#FED700',
                'link' => '/category/'.$slugs[array_rand($slugs)],
                'order' => 2,
                'text_position' => 'right',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'id' => 3,
                'title' => 'Slider Title',
                'title_color' => '#333E48',
                'sub_title' => 'Without span subtitle',
                'sub_title_color' => '#333E48',
                'description' => 'Slider description without span',
                'description_color' => '#868E8E',
                'alt_color' => '#FED700',
                'link' => '/category/'.$slugs[array_rand($slugs)],
                'order' => 3,
                'text_position' => 'left',
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'id' => 4,
                'title' => '',
                'title_color' => '#333E48',
                'sub_title' => '',
                'sub_title_color' => '#333E48',
                'description' => '',
                'description_color' => '#868E8E',
                'alt_color' => '#FED700',
                'link' => '',
                'order' => 4,
                'text_position' => 'right',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        if (File::isDirectory($this->demo_dir)) {
            $img_dirs = glob($this->demo_dir.'/sliders/*', GLOB_ONLYDIR);
            $appImages = glob($this->demo_dir.'/mobile-app/sliders/*.{jpg,png,jpeg}', GLOB_BRACE);

            $images = [];
            foreach ($img_dirs as $img_path) {
                $tempArr = explode('/', $img_path);
                $type = array_pop($tempArr);

                $images[$type] = glob($img_path.DIRECTORY_SEPARATOR.'*.{jpg,png,jpeg}', GLOB_BRACE);
            }

            $sliders = DB::table('sliders')->get();

            $appImgIndxs = array_rand($appImages, count($sliders));

            $i = 0;
            $images_data = [];
            foreach ($sliders as $slider) {
                $txt = $slider->title || $slider->sub_title ? $slider->text_position : 'text';
                $file = $images[$txt][array_rand($images[$txt])];
                $ext = pathinfo($file, PATHINFO_EXTENSION);

                $name = Str::random(10).'.'.$ext;
                $targetFile = $this->dir.DIRECTORY_SEPARATOR.$name;
                if ($this->disk->put($targetFile, file_get_contents($file))) {
                    $images_data[] = [
                        'name' => $name,
                        'path' => $targetFile,
                        'extension' => $ext,
                        'size' => filesize($file),
                        'order' => $slider->id,
                        'featured' => 1,
                        'type' => 'feature',
                        'imageable_id' => $slider->id,
                        'imageable_type' => \App\Models\Slider::class,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                // Mobile slider
                $file = $appImages[$appImgIndxs[$i]];
                $ext = pathinfo($file, PATHINFO_EXTENSION);

                $name = Str::random(10).'.'.$ext;
                $targetFile = $this->dir.DIRECTORY_SEPARATOR.$name;
                if ($this->disk->put($targetFile, file_get_contents($file))) {
                    $images_data[] = [
                        'name' => $name,
                        'path' => $targetFile,
                        'extension' => $ext,
                        'size' => filesize($file),
                        'order' => $slider->id,
                        'featured' => 0,
                        'type' => 'mobile',
                        'imageable_id' => $slider->id,
                        'imageable_type' => \App\Models\Slider::class,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                $i++;
            }

            // Insert all images at once
            DB::table('images')->insert($images_data);
        }
    }
}
