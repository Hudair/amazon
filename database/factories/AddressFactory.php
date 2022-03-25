<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $country_id = $this->faker->randomElement(\DB::table('countries')->pluck('id')->toArray());
        $state_id = $this->faker->randomElement(\DB::table('states')->where('country_id', $country_id)->pluck('id')->toArray());

        return [
            'address_title' => $this->faker->randomElement(['Home Address', 'Office Address', 'Hotel Address', 'Dorm Address']),
            'address_line_1' => $this->faker->streetAddress,
            'address_line_2' => $this->faker->streetName,
            'city' => $this->faker->city,
            'state_id' => $state_id,
            'zip_code' => $this->faker->postcode,
            'country_id' => $country_id,
            'phone' => $this->faker->phoneNumber,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ];
    }
}
