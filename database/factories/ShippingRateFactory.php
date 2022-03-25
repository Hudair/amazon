<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingRateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\ShippingRate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $delivery_takes = rand(1, 20);
        $based_on = $this->faker->randomElement(['price', 'weight']);

        return [
            'name' => $this->faker->word,
            'shipping_zone_id' => rand(1, 2),
            'carrier_id' => rand(1, 5),
            'delivery_takes' => $delivery_takes.'-'.($delivery_takes + rand(1, 20)).' days',
            'based_on' => $based_on,
            'minimum' => 0,
            'maximum' => $based_on == 'weight' ? 2000 : null,
            'rate' => rand(0, 20),
        ];
    }
}
