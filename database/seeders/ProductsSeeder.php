<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductsSeeder extends BaseSeeder
{
    private $longCount = 50;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()->create([
            'slug' => 'abc-xyz-product-123',
        ]);
        Product::factory()->count($this->longCount)->create();

        if (File::isDirectory($this->demo_dir)) {
            $products = DB::table('products')->pluck('id')->toArray();

            $img_dirs = glob($this->demo_dir.'/products/*', GLOB_ONLYDIR);

            $now = Carbon::Now();
            $data = [];

            foreach ($products as $item) {
                $images = glob($img_dirs[array_rand($img_dirs)].DIRECTORY_SEPARATOR.'*.{jpg,png,jpeg}', GLOB_BRACE);
                $i = 1;

                foreach ($images as $file) {
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                    $name = Str::random(10).'.'.$ext;
                    $targetFile = $this->dir.DIRECTORY_SEPARATOR.$name;

                    if ($this->disk->put($targetFile, file_get_contents($file))) {
                        $data[] = [
                            'name' => $name,
                            'path' => $targetFile,
                            'extension' => $ext,
                            'size' => filesize($file),
                            'featured' => ($i == 1),
                            'imageable_id' => $item,
                            'imageable_type' => Product::class,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                    $i++;
                }
            }

            DB::table('images')->insert($data);
        }
    }
}
