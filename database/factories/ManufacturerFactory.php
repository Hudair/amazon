<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ManufacturerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Manufacturer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique->company;

        return [
            'shop_id' => $this->faker->randomElement(\DB::table('shops')->pluck('id')->toArray()),
            'name' => $name,
            'slug' => Str::slug($name, '-'),
            'email' => $this->faker->unique->email,
            'url' => $this->faker->unique->url,
            'phone' => $this->faker->phoneNumber,
            'country_id' => $this->faker->randomElement(\DB::table('countries')->pluck('id')->toArray()),
            'description' => $this->faker->text(500),
            'active' => 1,
        ];
    }
}
