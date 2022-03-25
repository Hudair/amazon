<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TaxesSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('taxes')->insert([
            'shop_id' => null,
            'name' => '- No tax -',
            'public' => 1,
            'created_at' => Carbon::Now(),
            'updated_at' => Carbon::Now(),
        ]);
    }
}
