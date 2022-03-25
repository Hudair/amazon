<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\BlogComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'blog_id' => $this->faker->randomElement(\DB::table('blogs')->pluck('id')->toArray()),
            'content' => $this->faker->paragraph,
            'user_id' => $this->faker->randomElement(\DB::table('users')->pluck('id')->toArray()),
            'approved' => $this->faker->boolean,
            'created_at' => Carbon::Now()->subDays(rand(0, 15)),
            'updated_at' => Carbon::Now()->subDays(rand(0, 15)),
        ];
    }
}
