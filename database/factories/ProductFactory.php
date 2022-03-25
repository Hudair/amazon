<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $youtube = ['MLpWrANjFbI', 'TGbUpEJ1z-k', 'SKbHHcdLGKw', '7abGwVjXSY4', 'n7EmdNsKIhk', 'mATMpaPBBFI', 'AVpcxtH8hWg', 'tJlzIJaokVY', 'eEzD-Y97ges'];

        $vimeo = [
            'https://vimeo.com/channels/staffpicks/434009135',
            '104938952', '265254094', '380312199', '109952031',
        ];

        $images = [
            'https://loremflickr.com/640/360',
            'http://placeimg.com/640/360/any',
            'https://picsum.photos/640/360.webp',
        ];

        // Build description
        $desc = $this->faker->text(550);
        $desc .= str_replace('::LINK::', $this->faker->randomElement($youtube), '<p><br/><iframe frameborder="0" src="//www.youtube.com/embed/::LINK::" class="note-video-clip" style="width: 100%; height: 475px; float: none;"></iframe></p><br/>');
        $desc .= $this->faker->text(400);
        $desc .= str_replace('::LINK::', $this->faker->randomElement($images), '<img src="::LINK::" style="width: 50%; float: right; margin: 20px 0 20px 20px;">');
        $desc .= $this->faker->text(1200);
        $desc .= str_replace('::LINK::', $this->faker->randomElement($images), '<p><br/><img src="::LINK::" style="width: 100%; float: none;"></p><br/>');
        $desc .= $this->faker->text(600);

        return [
            'shop_id' => rand(0, 1) ? $this->faker->randomElement(DB::table('shops')->pluck('id')->toArray()) : Null,
            'manufacturer_id' => $this->faker->randomElement(DB::table('manufacturers')->pluck('id')->toArray()),
            'brand' => $this->faker->word,
            'name' => $this->faker->sentence,
            'model_number' => $this->faker->word . ' ' . $this->faker->bothify('??###'),
            'mpn' => $this->faker->randomNumber(null, false),
            'gtin' => $this->faker->ean13,
            'gtin_type' => $this->faker->randomElement(DB::table('gtin_types')->pluck('name')->toArray()),
            'description' => $desc,
            'origin_country' => $this->faker->randomElement(DB::table('countries')->pluck('id')->toArray()),
            'has_variant' => $this->faker->boolean,
            'slug' => $this->faker->slug,
            // 'meta_title' => $this->faker->sentence,
            // 'meta_description' => $this->faker->realText,
            'sale_count' => $this->faker->randomDigit,
            'active' => 1,
            'created_at' => Carbon::Now()->subDays(rand(0, 15)),
            'updated_at' => Carbon::Now()->subDays(rand(0, 15)),
        ];
    }
}
