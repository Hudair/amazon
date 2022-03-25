<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BlogSeeder extends BaseSeeder
{
    private $count = 5;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Blog::factory()->create([
            'slug' => 'abc-zcart-blog-post',
        ]);
        \App\Models\Blog::factory()->count($this->count)->create();

        if (File::isDirectory($this->demo_dir)) {
            $now = Carbon::Now();
            $data = [];
            $blogs = DB::table('blogs')->pluck('id')->toArray();

            foreach ($blogs as $blog) {
                $img = $this->demo_dir."/blogs/{$blog}.png";
                if (! file_exists($img)) {
                    continue;
                }

                $name = "blog_{$blog}.png";
                $targetFile = $this->dir.DIRECTORY_SEPARATOR.$name;

                if ($this->disk->put($targetFile, file_get_contents($img))) {
                    $data[] = [
                        'name' => $name,
                        'path' => $targetFile,
                        'extension' => 'png',
                        'featured' => 1,
                        'type' => 'cover',
                        'imageable_id' => $blog,
                        'imageable_type' => \App\Models\Blog::class,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            DB::table('images')->insert($data);
        }
    }
}
