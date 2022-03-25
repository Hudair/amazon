<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

class AddressTypesSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('address_types')->insert([
            [
                'type' => 'Primary',
            ], [
                'type' => 'Billing',
            ], [
                'type' => 'Shipping',
            ],
        ]);
    }
}
