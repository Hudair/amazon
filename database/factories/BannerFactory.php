<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Banner::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $desc = rand(19, 80).'% off';

        $frases = [
            'Flat '.$desc,
            'Up to '.$desc,
            $desc.' today!',
        ];

        $desc = $this->faker->randomElement($frases);
        $desc = rand(0, 4) ? $desc : $desc.' + free shipping';
        $desc = rand(0, 4) ? $desc : 'Don\'t miss out!';

        return [
            'title' => $this->faker->randomElement(['Deal of the day', 'Fashion accessories deals', 'Kids item deals', 'Year end SALE!', 'Black Friday Deals!', 'Books category deals', 'Free shipping', 'Tech accessories with free shipping', '80% Off!', 'Everyday essentials deals', 'Top deal on fashion accessories', 'Knockout offers!', 'BIG sale week!', 'Save up to 40%']),
            'description' => rand(0, 6) ? $desc : '',
            'link' => '/category/'.$this->faker->randomElement(\DB::table('categories')->pluck('slug')->toArray()),
            'link_label' => 'Shop Now',
            'effect' => 0,
            'bg_color' => $this->faker->hexcolor,
            'created_at' => Carbon::Now(),
            'updated_at' => Carbon::Now(),
        ];
    }
}
