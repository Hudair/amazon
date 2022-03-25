<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ConfigFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Config::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'support_email' => $this->faker->email,
            'default_sender_email_address' => $this->faker->email,
            'default_email_sender_name' => $this->faker->name,
            'support_phone' => $this->faker->phoneNumber,
            'support_phone_toll_free' => $this->faker->boolean ? $this->faker->tollFreePhoneNumber : null,
            'order_number_prefix' => '#',
            'default_tax_id' => rand(1, 31),
            'default_packaging_ids' => array_rand(range(1, 30), rand(1, 4)),
            'order_handling_cost' => rand(0, 1) ? rand(1, 5) : null,
            'maintenance_mode' => $this->faker->boolean,
        ];
    }
}
