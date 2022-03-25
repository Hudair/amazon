<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatesSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Get all of the countries
        $file_path = 'data/states';

        $files = glob(__DIR__.'/'.$file_path.'/'.'*.json');

        $now = Carbon::Now();
        foreach ($files as $file) {
            $country_code = basename($file, '.json'); // Get the the country iso_code from file name

            $country = DB::table('countries')->where('iso_code', $country_code)->first();

            //If the $country_id not found in countries table then ignore the file and move next
            if (! $country->id) {
                continue;
            }

            $json = json_decode(file_get_contents($file), true);

            // If the json decode returns null, for invalid json
            if (! $json) {
                continue;
            }

            // Sort data alphabetically
            usort($json, function ($a, $b) {
                return $a['name'] > $b['name'] ? 1 : -1;
            });

            $data = [];
            foreach ($json as $key => $state) {
                if (! isset($state['iso_code'])) {
                    continue;
                }

                $data[] = [
                    'country_id' => $country->id,
                    'name' => $state['name'],
                    'iso_code' => isset($state['iso_code']) ? $state['iso_code'] : null,
                    'iso_numeric' => isset($state['iso_numeric']) ? $state['iso_numeric'] : null,
                    // 'region' => isset($state['region']) ? $state['region'] : NULL,
                    // 'region_code' => isset($state['region_code']) ? $state['region_code'] : NULL,
                    'calling_code' => isset($state['calling_code']) ? $state['calling_code'] : null,
                    'active' => isset($state['active']) ? $state['active'] : 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (! empty($data)) {
                DB::table('states')->insert($data);
            }
        }
    }
}
