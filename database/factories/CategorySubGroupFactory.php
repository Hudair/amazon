<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategorySubGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\CategorySubGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_group_id' => $this->faker->randomElement(\DB::table('category_groups')->pluck('id')->toArray()),
            'name' => $this->faker->unique(true)->company,
            'slug' => $this->faker->unique(true)->slug,
            'description' => $this->faker->text(500),
            'active' => 1,
        ];
    }
}
