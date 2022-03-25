<?php

namespace Incevio\Package\Wallet\Database\Seeds;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletOptionsSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::Now();
        $table = get_option_table_name();
        $prefix = 'wallet_';
        // $faker = Faker::create();

    	$options = [
    		[
    			'option_name' => $prefix . 'min_withdrawal_limit',
    			'option_value' => 100,
    			'autoload' => true,
                'overwrite' => false
    		],[
    			'option_name' => $prefix . 'payment_methods',
    			'option_value' => serialize([]),
    			'autoload' => true,
                'overwrite' => false
    		],[
                'option_name' => $prefix . 'checkout',
                'option_value' => false,
                'autoload' => true,
                'overwrite' => false
            ],[
                'option_name' => $prefix . 'payment_info_cod',
                'option_value' => 'Manual payment info. Edit in settings section.',
                'autoload' => true,
                'overwrite' => false
            ],[
                'option_name' => $prefix . 'payment_info_wire',
                'option_value' => 'Manual payment instructions. Edit in settings section.',
                'autoload' => true,
                'overwrite' => false
            ],[
                'option_name' => $prefix . 'payment_instructions_cod',
                'option_value' => 'Manual payment instructions. Edit in settings section.',
                'autoload' => true,
                'overwrite' => false
            ],[
                'option_name' => $prefix . 'payment_instructions_wire',
                'option_value' => 'Manual payment instructions. Edit in settings section.',
                'autoload' => true,
                'overwrite' => false
    		],
            [
                'option_name' => $prefix . 'payout_fee',
                'option_value' => "Payout Fee",
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