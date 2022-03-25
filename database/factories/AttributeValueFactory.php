<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeValueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\AttributeValue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shop_id' => rand(1, 0) ? $this->faker->randomElement(\DB::table('shops')->pluck('id')->toArray()) : null,
            'value' => $this->faker->word,
            'color' => $this->faker->hexcolor,
            'attribute_id' => $this->faker->randomElement(\DB::table('attributes')->pluck('id')->toArray()),
            'order' => $this->faker->randomDigit,
        ];
    }
}
