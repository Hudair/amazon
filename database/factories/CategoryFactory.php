<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_sub_group_id' => $this->faker->randomElement(\DB::table('category_sub_groups')->pluck('id')->toArray()),
            'name' => $this->faker->unique(true)->company,
            'slug' => $this->faker->unique(true)->slug,
            'description' => $this->faker->text(80),
            'active' => 1,
        ];
    }
}
