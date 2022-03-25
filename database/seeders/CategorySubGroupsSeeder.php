<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// use Illuminate\Support\Facades\File;

class CategorySubGroupsSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category_sub_groups')->insert([
            [
                'category_group_id' => 1,
                'name' => 'Mobile & Accessories',
                'slug' => 'mobile-accessories',
                'description' => 'Cell Phones and Accessories',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'category_group_id' => 1,
                'name' => 'Computer & Accessories',
                'slug' => 'computer-accessories',
                'description' => 'Tablet, Laptop, Desktop and Accessories',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'category_group_id' => 1,
                'name' => 'Home Entertainment',
                'slug' => 'home-entertainment',
                'description' => 'TVs, Home Theaters etc',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'category_group_id' => 1,
                'name' => 'Photo & Video',
                'slug' => 'photo-video',
                'description' => 'PnS, DSLR, Video Camera and Accessories',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'category_group_id' => 2,
                'name' => 'Indoor',
                'slug' => 'indoor',
                'description' => 'Puzzle, Keram etc',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'category_group_id' => 2,
                'name' => 'Outdoor',
                'slug' => 'outdoor',
                'description' => 'Cycle, Dron etc',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'category_group_id' => 3,
                'name' => 'Men\'s Fashion',
                'slug' => 'mens-fashion',
                'description' => 'Lots of fashion products.',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'category_group_id' => 3,
                'name' => 'Women\'s Fashion',
                'slug' => 'womens-fashion',
                'description' => 'Lots of fashion products.',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'category_group_id' => 4,
                'name' => 'Kitchen',
                'slug' => 'kitchen',
                'description' => 'Kitchen and cooking products.',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ], [
                'category_group_id' => 4,
                'name' => 'Garden',
                'slug' => 'garden',
                'description' => 'Gardening related products.',
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ],
        ]);
    }
}
