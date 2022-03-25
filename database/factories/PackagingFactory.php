<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackagingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Packaging::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shop_id' => $this->faker->randomElement(\DB::table('shops')->pluck('id')->toArray()),
            'name' => $this->faker->word,
            'cost' => rand(1, 10),
            'width' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 10, $max = 50),
            'height' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 10, $max = 60),
            'depth' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 10, $max = 40),
            'created_at' => Carbon::Now()->subDays(rand(0, 15)),
            'updated_at' => Carbon::Now()->subDays(rand(0, 15)),
        ];
    }
}
