<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class WarehouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Warehouse::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $shop_id = $this->faker->randomElement(\DB::table('shops')->pluck('id')->toArray());

        return [
            'shop_id' => $shop_id,
            'incharge' => DB::table('users')->select('id')->where('shop_id', $shop_id)->first()->id,
            'name' => $this->faker->company,
            'email' => $this->faker->email,
            'description' => $this->faker->text(500),
            'active' => 1,
        ];
    }
}
