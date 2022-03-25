<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DashboardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Dashboard::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'upgrade_plan_notice' => true,
        ];
    }
}
