<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaxFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Tax::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $num = $this->faker->randomFloat($nbMaxDecimals = null, $min = 2, $max = 9);
        $country_id = $this->faker->randomElement(\DB::table('countries')->pluck('id')->toArray());
        $state_id = $this->faker->randomElement(\DB::table('states')->where('country_id', $country_id)->pluck('id')->toArray());

        return [
            'shop_id' => $this->faker->randomElement(\DB::table('shops')->pluck('id')->toArray()),
            'name' => $this->faker->word.' '.round($num, 2).'%',
            'country_id' => $country_id,
            'state_id' => $state_id,
            'taxrate' => $num,
            'active' => 1,
        ];
    }
}
