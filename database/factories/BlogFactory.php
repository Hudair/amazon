<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Blog::class;

    public $imgs = [
        '<img src="https://farm5.staticflickr.com/4489/37743618362_594eceff0b_b.jpg" width="640" height="424" alt="Apple tree V1">',
        '<img src="https://farm5.staticflickr.com/4459/37473350250_be9bb89c24_b.jpg" width="640" height="424" alt="Sunrise at red crabs beach">',
        '<img src="https://farm5.staticflickr.com/4514/37473352560_7da215a7a5_z.jpg" width="640" height="424" alt="Follow me">',
        '<img src="https://farm5.staticflickr.com/4494/37473356390_051bb9b747_z.jpg" width="424" height="640" alt="Discrete wheel V1">',
        '<img src="https://farm5.staticflickr.com/4472/37699097482_c20aaa2910_z.jpg" width="640" height="424" alt="Rolling">',
        '<img src="https://farm5.staticflickr.com/4510/37699099812_bc4aa78c5a_z.jpg" width="640" height="424" alt="Fishermen&#x27;s morning V1">',
        '<img src="https://farm5.staticflickr.com/4471/37699101012_7b2c5c2734_z.jpg" width="640" height="424" alt="Dogs on beach V1">',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/e9dZQelULDk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/A-rEb0KuopI" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
        '<iframe width="560" height="315" src="https://www.youtube.com/embed/qUGHv2VAESE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $now = Carbon::Now();

        return [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'excerpt' => $this->faker->paragraph,
            'content' => '<p>'.$this->faker->paragraph.'</p><p>'.$this->faker->paragraph.'</p><p>'.$this->faker->paragraph.'</p><p>'.$this->faker->paragraph.$this->imgs[array_rand($this->imgs)].'</p><p>'.$this->faker->paragraph.'</p><p>'.$this->faker->paragraph.'</p><p>'.$this->faker->paragraph.'</p>',
            // 'content' => $this->faker->paragraphs(10, $asText = true),
            'user_id' => $this->faker->randomElement(\DB::table('users')->pluck('id')->toArray()),
            'status' => 1,
            'published_at' => $now->subDays(rand(1, 15))->format('Y-m-d h:i a'),
            'created_at' => $now->subDays(rand(0, 15)),
            'updated_at' => $now->subDays(rand(0, 15)),
        ];
    }
}
