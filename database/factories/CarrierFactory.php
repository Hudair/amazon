<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CarrierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Carrier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shop_id' => $this->faker->randomElement(\DB::table('shops')->pluck('id')->toArray()),
            'tax_id' => $this->faker->randomElement(\DB::table('taxes')->pluck('id')->toArray()),
            'name' => $this->faker->randomElement(['DHL', 'FedEx', 'USP', 'TNT Express', 'USPS', 'YRC', 'DTDC']),
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'tracking_url' => $this->faker->url.'/@',
            'active' => 1,
        ];
    }
}
