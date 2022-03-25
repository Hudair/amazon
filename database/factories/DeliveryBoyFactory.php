<?php

namespace Database\Factories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryBoyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shop_id'      => $this->faker->randomElement(DB::table('shops')->pluck('id')->toArray()),
            'first_name'    => $this->faker->firstName,
            'last_name'    => $this->faker->lastName,
            'nice_name'    => $this->faker->lastName,
            'phone_number' => $this->faker->phoneNumber,
            'email'        => $this->faker->email,
            'password'     => bcrypt('123456'),
            'status'       => 1
        ];
    }
}
