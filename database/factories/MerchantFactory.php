<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MerchantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Merchant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $created_at = Carbon::Now()->subDays(rand(0, 15));

        return [
            'shop_id' => 1,
            'role_id' => \App\Models\Role::MERCHANT,
            'nice_name' => $this->faker->lastName,
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt(123456),
            'dob' => $this->faker->date,
            'sex' => $this->faker->randomElement(['app.male', 'app.female']),
            'description' => $this->faker->text(500),
            'active' => 1,
            // 'remember_token' => Str::random(10),
            // 'verification_token' => rand(0,1) == 1 ? Null : Str::random(10),
            'created_at' => $created_at,
            'updated_at' => $created_at,
        ];
    }
}
