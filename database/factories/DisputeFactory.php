<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class DisputeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Dispute::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $order = $this->faker->randomElement(\DB::table('orders')->get()->toArray());

        return [
            'dispute_type_id' => rand(1, 7),
            'shop_id' => $order->shop_id,
            'customer_id' => $order->customer_id,
            'order_id' => $order->id,
            'description' => $this->faker->text(100),
            'return_goods' => $this->faker->boolean,
            'order_received' => $this->faker->boolean,
            'status' => rand(1, 6),
            'created_at' => Carbon::Now()->subMonths(rand(0, 5)),
            'updated_at' => Carbon::Now()->subMonths(rand(0, 5)),
        ];
    }
}
