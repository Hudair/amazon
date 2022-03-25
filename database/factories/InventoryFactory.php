<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Inventory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $num = $this->faker->randomFloat($nbMaxDecimals = null, $min = 50, $max = 400);
        $sale_price = $num + rand(1, 200);
        $offer_price = rand(1, 0) ? $sale_price - rand(1, $num) : null;

        return [
            'shop_id' => $this->faker->randomElement(\DB::table('shops')->pluck('id')->toArray()),
            'title' => $this->faker->sentence,
            'brand' => $this->faker->randomElement(\DB::table('manufacturers')->pluck('name')->toArray()),
            'sku' => $this->faker->word,
            'condition' => $this->faker->randomElement(['New', 'Used', 'Refurbished']),
            'condition_note' => $this->faker->realText,
            'description' => $this->faker->text(1500),
            'key_features' => [$this->faker->sentence, $this->faker->sentence, $this->faker->sentence, $this->faker->sentence, $this->faker->sentence, $this->faker->sentence, $this->faker->sentence],
            'stock_quantity' => rand(9, 99),
            'damaged_quantity' => 0,
            'product_id' => $this->faker->randomElement(\DB::table('products')->pluck('id')->toArray()),
            'supplier_id' => $this->faker->randomElement(\DB::table('suppliers')->pluck('id')->toArray()),
            'user_id' => 3,
            'purchase_price' => $num,
            'sale_price' => $sale_price,
            'offer_price' => $offer_price,
            'offer_start' => $offer_price ? Carbon::Now()->format('Y-m-d h:i a') : null,
            'offer_end' => $offer_price ? date('Y-m-d h:i a', strtotime(rand(3, 22).' days')) : null,
            'min_order_quantity' => 1,
            'shipping_weight' => rand(100, 1999),
            'free_shipping' => $this->faker->boolean,
            'linked_items' => array_rand(range(1, 50), rand(2, 3)),
            'available_from' => Carbon::Now()->subDays(rand(1, 3))->format('Y-m-d h:i a'),
            'slug' => $this->faker->slug,
            'meta_title' => $this->faker->sentence,
            'meta_description' => $this->faker->realText,
            'stuff_pick' => $this->faker->boolean,
            'active' => 1,
            'created_at' => Carbon::Now()->subDays(rand(0, 15)),
            'updated_at' => Carbon::Now()->subDays(rand(0, 15)),
        ];
    }
}
