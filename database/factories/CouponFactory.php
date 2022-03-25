<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Coupon::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shop_id' => $this->faker->randomElement(\DB::table('shops')->pluck('id')->toArray()),
            'name' => $this->faker->word,
            'code' => $this->faker->unique->randomNumber(),
            'description' => $this->faker->text(1500),
            'value' => rand(9, 99),
            'type' => $this->faker->randomElement(['amount', 'percent']),
            // 'limited' => $this->faker->boolean,
            'quantity' => rand(1, 100),
            'quantity_per_customer' => rand(1, 5),
            'starting_time' => date('Y-m-d h:i a', strtotime(rand(0, 7).' days')),
            'ending_time' => date('Y-m-d h:i a', strtotime(rand(7, 22).' days')),
            // 'partial_use' => $this->faker->boolean,
            // 'exclude_offer_items' => $this->faker->boolean,
            // 'exclude_tax_n_shipping' => $this->faker->boolean,
            'created_at' => Carbon::Now()->subDays(rand(0, 15)),
            'updated_at' => Carbon::Now()->subDays(rand(0, 15)),
        ];
    }
}
