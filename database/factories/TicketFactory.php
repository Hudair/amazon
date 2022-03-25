<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shop_id' => $this->faker->randomElement(\DB::table('shops')->pluck('id')->toArray()),
            'user_id' => 3,
            'category_id' => $this->faker->randomElement(\DB::table('ticket_categories')->pluck('id')->toArray()),
            'subject' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'status' => rand(1, 6),
            'priority' => rand(1, 4),
            'created_at' => Carbon::Now()->subDays(rand(0, 15)),
            'updated_at' => Carbon::Now()->subDays(rand(0, 15)),
        ];
    }
}
