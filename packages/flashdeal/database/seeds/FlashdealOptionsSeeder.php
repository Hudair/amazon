<?php

namespace Incevio\Package\Flashdeal\Database\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlashdealOptionsSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::Now();
        $table = get_option_table_name();
        $prefix = 'flashdeal_';

    	$options = [
    		[
    			'option_name' => $prefix . 'items',
    			'option_value' => serialize([]),
    			'autoload' => true,
                'overwrite' => false
    		]
    	];

        foreach($options as $option) {
            $common = [
                'option_value' => $option['option_value'],
                'autoload' => $option['autoload'],
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if(DB::table($table)->where('option_name', $option['option_name'])->first()) {
                if ($option['overwrite']) {
                    DB::table($table)->where('option_name', $option['option_name'])->update($common);
                }
            }
            else {
                DB::table($table)->insert(array_merge($common, ['option_name' => $option['option_name']]));
            }
        }
    }
}