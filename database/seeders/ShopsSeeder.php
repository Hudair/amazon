<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ShopsSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country_ids = [50, 840];
        $state_ids = DB::table('states')->whereIn('country_id', $country_ids)->pluck('id')->toArray();
        $merchants = DB::table('users')->where('role_id', \App\Models\Role::MERCHANT)->pluck('id')->toArray();

        $shop_names = [
            3 => 'Big Shop',
            4 => 'Amz Mart',
            5 => 'Peda Ting Ting',
            6 => 'Phuket Retailer',
            7 => 'Tindu Gears',
        ];

        $plan_id = null;
        $trial_ends_at = null;
        $now = Carbon::Now();
        $images_data = [];
        $addresses = [];
        $shipping_zones = [];

        if (is_subscription_enabled()) {
            $plan_id = 'price_1GyyRyJewI4n8wVFSRWlMSHy';
            $trial_ends_at = $now->addDays(13);
        }

        $faker = Faker::create();

        foreach ($merchants as $merchant) {
            $shop_name = array_key_exists($merchant, $shop_names) ? $shop_names[$merchant] : 'Demo Shop ' . $merchant;

            $shop_id = DB::table('shops')->insertGetId([
                'owner_id' => $merchant,
                'name' => $shop_name,
                'legal_name' => $shop_name . ' Ltd.',
                'slug' => Str::slug($shop_name, '-'),
                'email' => 'shop' . $merchant . '@demo.com',
                'current_billing_plan' => $plan_id,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'timezone_id' => 73,
                'active' => 1,
                'payment_verified' => 1,
                'id_verified' => 1,
                'phone_verified' => 1,
                'address_verified' => 1,
                'trial_ends_at' => $trial_ends_at,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $subscriptions[] = [
                'shop_id' => $shop_id,
                'name' => 'Business',
                'stripe_price' => $plan_id,
                'quantity' => 1,
                'trial_ends_at' => $trial_ends_at,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $addresses[] = [
                'address_type' => 'Primary',
                'addressable_type' => \App\Models\Shop::class,
                'address_line_1' => 'Demo Platform Address',
                'city' => 'Demo City',
                'state_id' => 380,
                'zip_code' => 63585,
                'country_id' => 604,
                'phone' => '+1.431.253.0341',
                'addressable_id' => $shop_id,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $configs[] = [
                'shop_id' => $shop_id,
                'support_email' => 'support.shop@demo.com',
                'default_sender_email_address' => 'noreply.shop@demo.com',
                'default_email_sender_name' => 'Support Agent',
                'return_refund' => '<h3>Return & Refund Policy</h3> Thanks for shopping at My Shop.<br/> If you are not entirely satisfied with your purchase, we\'re here to help.<br/><br/><h3>Returns</h3>You have 30 (change this) calendar days to return an item from the date you received it.<br/>To be eligible for a return, your item must be unused and in the same condition that you received it.<br/>Your item must be in the original packaging.<br/>Your item needs to have the receipt or proof of purchase.<br/><br/>',
                'order_number_prefix' => '#',
                'default_tax_id' => 1,
                'default_packaging_ids' => serialize(array_rand(range(1, 6), 3)),
                'order_handling_cost' => 2,
                'maintenance_mode' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $shipping_zones[] = [
                'shop_id' => $shop_id,
                'name' => 'Domestic',
                'tax_id' => 1,
                'country_ids' => serialize($country_ids),
                'state_ids' => serialize($state_ids),
                'rest_of_the_world' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $shipping_zones[] = [
                'shop_id' => $shop_id,
                'name' => 'Worldwide',
                'tax_id' => 1,
                'country_ids' => null,
                'state_ids' => null,
                'rest_of_the_world' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $delivery_boys[] = [
                'shop_id' => $shop_id,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'nice_name' => $faker->lastName,
                'phone_number' => $faker->phoneNumber,
                'email' => 'delivery' . $shop_id . '@demo.com',
                'password' => bcrypt('123456'),
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (File::isDirectory($this->demo_dir)) {
                $logos = glob($this->demo_dir . '/logos/*.png');

                if (count($logos) > 0) {
                    $file = $logos[array_rand($logos)];
                    $ext = pathinfo($file, PATHINFO_EXTENSION);

                    $name = Str::random(10) . '.' . $ext;
                    $targetFile = $this->dir . DIRECTORY_SEPARATOR . $name;

                    if ($this->disk->put($targetFile, file_get_contents($file))) {
                        $images_data[] = [
                            'name' => $name,
                            'path' => $targetFile,
                            'extension' => $ext,
                            'featured' => 0,
                            'type' => 'logo',
                            'imageable_id' => $shop_id,
                            'imageable_type' => \App\Models\Shop::class,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
            }
        }

        // Insert all data at once
        DB::table('subscriptions')->insert($subscriptions);
        DB::table('addresses')->insert($addresses);
        DB::table('configs')->insert($configs);
        DB::table('shipping_zones')->insert($shipping_zones);
        DB::table('delivery_boys')->insert($delivery_boys);

        if (count($images_data) > 0) {
            DB::table('images')->insert($images_data);
        }
    }
}
