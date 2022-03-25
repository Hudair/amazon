<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Reply::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomElement(\DB::table('users')->pluck('id')->toArray()),
            'reply' => $this->faker->paragraph,
            'read' => $this->faker->boolean,
            'repliable_id' => rand(1, 5),
            'repliable_type' => rand(0, 1) == 1 ? \App\Models\Ticket::class : \App\Models\Message::class,
            'created_at' => Carbon::Now()->subDays(rand(0, 15)),
            'updated_at' => Carbon::Now()->subDays(rand(0, 15)),
        ];
    }
}
