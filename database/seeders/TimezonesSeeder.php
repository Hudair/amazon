<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TimezonesSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Get all of the timezones
        $timezones = json_decode(file_get_contents(__DIR__.'/data/timezones.json'), true);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\Timezone::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($timezones as $timezone) {
            DB::table('timezones')->insert([
                'value' => isset($timezone['value']) ? $timezone['value'] : null,
                'abbr' => isset($timezone['abbr']) ? $timezone['abbr'] : null,
                'offset' => isset($timezone['offset']) ? $timezone['offset'] : null,
                'text' => isset($timezone['text']) ? $timezone['text'] : null,
                'utc' => isset($timezone['utc']) ? $timezone['utc'] : null,
                'dst' => isset($timezone['dst']) ? $timezone['dst'] : null,
                'created_at' => Carbon::Now(),
                'updated_at' => Carbon::Now(),
            ]);
        }
    }
}
