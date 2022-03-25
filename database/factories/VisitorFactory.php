<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Visitor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $num2 = rand(1, 999);
        $time = Carbon::Now()->subMonths(rand(0, 6));

        return [
            'ip' => $this->faker->unique()->ipv4,
            'mac' => $this->faker->unique()->macAddress,
            'hits' => $num2,
            'page_views' => $num2 + rand(0, 999),
            'country_code' => $this->faker->countryCode,
            'created_at' => $time,
            'updated_at' => $time,
        ];
    }
}
