<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Feedback::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = rand(0, 1) == 1 ? \App\Models\Shop::class : \App\Models\Inventory::class;
        $feedbackable = $type == \App\Models\Shop::class ? 'shops' : 'inventories';
        $feedbackable_id = \DB::table($feedbackable)->pluck('id')->toArray();

        return [
            'customer_id' => $this->faker->randomElement(\DB::table('customers')->pluck('id')->toArray()),
            'rating' => rand(1, 5),
            'comment' => $this->faker->paragraph,
            'feedbackable_id' => $this->faker->randomElement($feedbackable_id),
            'feedbackable_type' => $type,
            'created_at' => Carbon::Now()->subDays(rand(0, 15)),
            'updated_at' => Carbon::Now()->subDays(rand(0, 15)),
        ];
    }
}
