<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BannersSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Banner::factory()->count(3)->create([
            'effect' => 1,
            'columns' => 4,
            'group_id' => 'group_1',
        ]);
        \App\Models\Banner::factory()->count(2)->create([
            'columns' => 6,
            'group_id' => 'group_2',
        ]);
        // \App\Models\Banner::factory()->create([
        //     'columns' => 12,
        //     'group_id' => 'group_3'
        // ]);
        \App\Models\Banner::factory()->count(2)->create([
            'columns' => 6,
            'group_id' => 'group_4',
        ]);
        \App\Models\Banner::factory()->create([
            'columns' => 12,
            'group_id' => 'group_5',
        ]);
        \App\Models\Banner::factory()->count(2)->create([
            'effect' => 1,
            'columns' => 6,
            'group_id' => 'group_6',
        ]);

        if (File::isDirectory($this->demo_dir)) {
            $banners = DB::table('banners')->count();

            $images = glob($this->demo_dir.'/banners/*.{jpg,png,jpeg}', GLOB_BRACE);
            natsort($images);
            $data = [];
            $now = Carbon::Now();
            $i = 0;

            foreach ($images as $file) {
                $i++;
                if ($i > $banners) {
                    break;
                }

                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $name = Str::random(10).'.'.$ext;
                $targetFile = $this->dir.DIRECTORY_SEPARATOR.$name;

                if ($this->disk->put($targetFile, file_get_contents($file))) {
                    $data[] = [
                        'name' => $name,
                        'path' => $targetFile,
                        'extension' => $ext,
                        'featured' => 1,
                        'type' => 'feature',
                        'imageable_id' => $i,
                        'imageable_type' => \App\Models\Banner::class,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            DB::table('images')->insert($data);
        }
    }
}
