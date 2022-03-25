<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shop_id' => $this->faker->randomElement(\DB::table('shops')->pluck('id')->toArray()),
            'name' => $this->faker->company,
            'email' => $this->faker->email,
            'contact_person' => $this->faker->name,
            'url' => $this->faker->url,
            'description' => $this->faker->text(500),
            'active' => 1,
        ];
    }
}
