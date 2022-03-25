<?php

namespace Incevio\Package\DynamicCommission\Database\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DynamicCommissionOptionsSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::Now();
        $table = get_option_table_name();
        $prefix = 'dynamicCommission_';

    	$options = [
    		[
    			'option_name' => $prefix . 'milestones',
    			'option_value' => serialize([
                    ['milestone' => 0, 'commission' => 0],
                ]),
    			'autoload' => true,
                'overwrite' => false
            ],[
                'option_name' => $prefix . 'reset_on_payout',
                'option_value' => 0,
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