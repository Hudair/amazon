<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class GiftCardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\GiftCard::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start = rand(-6, 6);
        $end = rand(6, 24);
        $value = rand(10, 100);

        return [
            'name' => $this->faker->word,
            'description' => $this->faker->text(1500),
            'value' => $value,
            'remaining_value' => $value - rand(0, $value),
            'serial_number' => $this->faker->unique->randomNumber(),
            'pin_code' => $this->faker->unique->randomNumber(),
            'activation_time' => date('Y-m-d h:i a', strtotime($start.' months')),
            'expiry_time' => date('Y-m-d h:i a', strtotime($end.' months')),
            'partial_use' => $this->faker->boolean,
            'exclude_offer_items' => $this->faker->boolean,
            'exclude_tax_n_shipping' => $this->faker->boolean,
            'created_at' => Carbon::Now()->subDays(rand(0, 15)),
            'updated_at' => Carbon::Now()->subDays(rand(0, 15)),
        ];
    }
}
