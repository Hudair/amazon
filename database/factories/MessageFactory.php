<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $shop_id = rand(1, 2);

        return [
            // 'shop_id' => $this->faker->randomElement(\DB::table('shops')->pluck('id')->toArray()),
            // 'customer_id' => $this->faker->randomElement(\DB::table('customers')->pluck('id')->toArray()),
            'shop_id' => $shop_id,
            'customer_id' => 1,
            'order_id' => $this->faker->randomElement(\DB::table('orders')->where('shop_id', $shop_id)->pluck('id')->toArray()),
            'subject' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'status' => rand(1, 3),
            'label' => rand(1, 5),
            'created_at' => Carbon::Now()->subDays(rand(0, 15)),
            'updated_at' => Carbon::Now()->subDays(rand(0, 15)),
        ];
    }
}
