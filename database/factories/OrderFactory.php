<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $num = $this->faker->randomFloat($nbMaxDecimals = null, $min = 100, $max = 400);
        $num2 = rand(1, 9);
        $customer_id = $this->faker->randomElement(\DB::table('customers')->pluck('id')->toArray());
        $billing_address = \App\Models\Address::where('addressable_type', \App\Models\Customer::class)
            ->where('addressable_id', $customer_id)->first()->toHtml('<br/>', false);

        $time = Carbon::Now()->subDays(rand(0, 30));

        return [
            'shop_id' => $this->faker->randomElement(\DB::table('shops')->pluck('id')->toArray()),
            'order_number' => '#'.str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
            'customer_id' => $customer_id,
            'shipping_rate_id' => $this->faker->randomElement(\DB::table('shipping_rates')->pluck('id')->toArray()),
            'packaging_id' => $this->faker->randomElement(\DB::table('packagings')->pluck('id')->toArray()),
            'item_count' => $num2,
            'quantity' => $num2,
            'shipping_weight' => rand(100, 999),
            'total' => $num,
            'shipping' => $num2,
            'grand_total' => $num2 + $num,
            'billing_address' => $billing_address,
            'shipping_address' => $billing_address,
            'tracking_id' => 'RR123456789CN',
            'payment_method_id' => $this->faker->randomElement(\DB::table('payment_methods')->pluck('id')->toArray()),
            'admin_note' => $this->faker->sentence,
            'buyer_note' => $this->faker->sentence,
            'created_at' => $time,
            'updated_at' => $time,
        ];
    }
}
