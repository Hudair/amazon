<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Get all of the countries
        $countries = json_decode(file_get_contents(__DIR__.'/data/countries.json'), true);

        $data = [];
        $now = Carbon::Now();
        foreach ($countries as $countryId => $country) {
            if (! isset($country['iso_code'])) {
                continue;
            }

            if (isset($country['currency_code'])) {
                $currency = DB::table('currencies')->select('id')
                    ->where('iso_code', $country['currency_code'])->first();
            }

            // if(isset($country['currency_code'])){
            //     $currency = DB::table('currencies')->select('id')
            //     ->where('iso_code', $country['currency_code'])->first();
            // }

            $data[] = [
                'id' => $countryId,
                'name' => $country['name'],
                'full_name' => isset($country['full_name']) ? $country['full_name'] : null,
                'capital' => isset($country['capital']) ? $country['capital'] : null,
                'timezone_id' => isset($timezone) && $timezone ? $timezone->id : null,
                'currency_id' => isset($currency) && $currency ? $currency->id : null,
                'citizenship' => isset($country['citizenship']) ? $country['citizenship'] : null,
                'iso_code' => $country['iso_code'],
                'iso_numeric' => isset($country['iso_numeric']) ? $country['iso_numeric'] : null,
                'calling_code' => $country['calling_code'],
                'flag' => isset($country['flag']) ? $country['flag'] : null,
                'eea' => (bool) $country['eea'],
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (! empty($data)) {
            DB::table('countries')->insert($data);
        }
    }
}
