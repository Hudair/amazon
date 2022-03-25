<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class WishlistFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Wishlist::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $inventory = $this->faker->randomElement(\DB::table('inventories')->select('product_id', 'id')->get()->toArray());
        // $inventory_id = $this->faker->randomElement(\DB::table('inventories')->pluck('id')->toArray());
        $time = Carbon::Now()->subDays(rand(0, 15));

        return [
            'customer_id' => 1,
            'product_id' => $inventory->product_id,
            'inventory_id' => $inventory->id,
            'created_at' => $time,
            'updated_at' => $time,
        ];
    }
}
