<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $time = Carbon::Now()->subMonths(rand(12, 25));

        return [
            'nice_name' => $this->faker->lastName,
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt('123456'),
            'dob' => $this->faker->date,
            'sex' => $this->faker->randomElement(['app.male', 'app.female', 'app.other']),
            'description' => $this->faker->text(200),
            'active' => 1,
            'remember_token' => Str::random(10),
            'verification_token' => null,
            'created_at' => $time,
            'updated_at' => $time,
        ];
    }
}
