<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class ShippingZoneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\ShippingZone::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $country_ids = $this->faker->randomElements(\DB::table('countries')->pluck('id')->toArray(), 3);
        $state_ids = DB::table('states')->whereIn('country_id', $country_ids)->pluck('id')->toArray();

        return [
            'name' => 'Domestic',
            'tax_id' => 1,
            'country_ids' => $country_ids,
            'state_ids' => $state_ids,
        ];
    }
}
