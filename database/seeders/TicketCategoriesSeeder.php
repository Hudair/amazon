<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

class TicketCategoriesSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ticket_categories')->insert([
            [
                'name' => 'General query',
                // 'priority' => 'Low'
            ], [
                'name' => 'Merchant support',
                // 'priority' => 'Medium'
            ], [
                'name' => 'Technical support',
                // 'priority' => 'High'
            ], [
                'name' => 'Webmaster',
                // 'priority' => 'Critical'
            ],
        ]);
    }
}
