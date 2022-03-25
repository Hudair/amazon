<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionPlansSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::Now();

        DB::table('subscription_plans')->insert([
            [
                'name' => 'Individual',
                'plan_id' => 'price_1H1HUQJewI4n8wVFTnjx77Ws',
                'cost' => 9,
                'transaction_fee' => 2.5,
                'marketplace_commission' => 3,
                'team_size' => 1,
                'inventory_limit' => 20,
                'featured' => false,
                'order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'name' => 'Business',
                'plan_id' => 'price_1GyyRyJewI4n8wVFSRWlMSHy',
                'cost' => 29,
                'transaction_fee' => 1.9,
                'marketplace_commission' => 2.5,
                'team_size' => 5,
                'inventory_limit' => 200,
                'featured' => true,
                'order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'name' => 'Professional',
                'plan_id' => 'price_1H1HW7JewI4n8wVFl8Ukknoz',
                'cost' => 49,
                'transaction_fee' => 1,
                'marketplace_commission' => 1.5,
                'team_size' => 10,
                'inventory_limit' => 500,
                'featured' => false,
                'order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
