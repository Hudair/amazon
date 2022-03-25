<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

class BannerGroupsSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('banner_groups')->insert([
            [
                'id'   => 'group_1',
                'name' => 'Group 1',
            ], [
                'id'   => 'group_2',
                'name' => 'Group 2',
            ], [
                'id'   => 'group_3',
                'name' => 'Group 3',
            ], [
                'id'   => 'group_4',
                'name' => 'Group 4',
            ], [
                'id'   => 'group_5',
                'name' => 'Group 5',
            ], [
                'id'   => 'group_6',
                'name' => 'Group 6',
            ],
        ]);
    }
}
