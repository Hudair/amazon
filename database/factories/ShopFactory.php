<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class ShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Shop::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $company = $this->faker->unique->company;
        $merchnats = DB::table('users')->where('role_id', \App\Models\Role::MERCHANT)->where('id', '>', 4)->pluck('id')->toArray();
        $created_at = Carbon::Now()->subDays(rand(0, 15));

        return [
            'owner_id' => empty($merchnats) ? null : $this->faker->randomElement($merchnats),
            'name' => $company,
            'legal_name' => $company,
            'slug' => $this->faker->slug,
            'email' => $this->faker->email,
            'description' => $this->faker->text(500),
            'external_url' => $this->faker->url,
            'timezone_id' => $this->faker->randomElement(\DB::table('timezones')->pluck('id')->toArray()),
            'active' => $this->faker->boolean,
            'created_at' => $created_at,
            'updated_at' => $created_at,
        ];
    }
}
