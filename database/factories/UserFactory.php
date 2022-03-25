<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shop_id' => $this->faker->randomElement(\DB::table('shops')->pluck('id')->toArray()),
            'role_id' => $this->faker->randomElement(\DB::table('roles')->whereNotIn('id', [1, 3])->pluck('id')->toArray()),
            'nice_name' => $this->faker->lastName,
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt('123456'),
            'dob' => $this->faker->date,
            'sex' => $this->faker->randomElement(['app.male', 'app.female']),
            'description' => $this->faker->text(500),
            'active' => $this->faker->boolean,
            'remember_token' => Str::random(10),
            'created_at' => Carbon::Now()->subDays(rand(0, 15)),
            'updated_at' => Carbon::Now()->subDays(rand(0, 15)),
        ];
    }
}
