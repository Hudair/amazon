<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class demoSeeder extends BaseSeeder
{
    private $tinycount = 5;
    private $count = 15;
    private $longCount = 30;
    private $longLongCount = 50;
    private $veryLongCount = 150;
    private $now;

    public function __construct()
    {
        $this->now = Carbon::Now();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images_data = [];

        // \App\Models\Role::factory()->count($this->tinycount)->create();

        \App\Models\User::factory()->count(1)
            ->create([
                'id' => 2,
                'shop_id' => null,
                'role_id' => \App\Models\Role::ADMIN,
                'nice_name' => 'Admin',
                'name' => 'Admin User',
                'email' => 'admin@demo.com',
                'password' => bcrypt('123456'),
                'active' => 1,
            ])
            ->each(function ($user) {
                $user->dashboard()->save(\App\Models\Dashboard::factory()->make());

                $user->addresses()->save(
                    \App\Models\Address::factory()->make(['address_title' => $user->name, 'address_type' => 'Primary'])
                );
            });

        $this->call(VendorsSeeder::class);

        \App\Models\DeliveryBoy::factory()->count(1)
            ->create([
                'shop_id' => 1,
                'email' => 'delivery@demo.com'
            ]);

        \App\Models\Customer::factory()->count(1)
            ->create([
                'id' => 1,
                'email' => 'customer@demo.com',
                'password' => bcrypt('123456'),
                'sex' => 'app.male',
                'active' => 1,
            ])
            ->each(function ($customer) {
                $customer->addresses()->save(\App\Models\Address::factory()->make(['address_title' => $customer->name, 'address_type' => 'Primary']));
                $customer->addresses()->save(\App\Models\Address::factory()->make(['address_type' => 'Billing']));
                $customer->addresses()->save(\App\Models\Address::factory()->make(['address_type' => 'Shipping']));
            });
        \App\Models\Customer::factory()->count($this->count)
            ->create()
            ->each(function ($customer) {
                $customer->addresses()->save(\App\Models\Address::factory()->make(['address_title' => $customer->name, 'address_type' => 'Primary']));
                $customer->addresses()->save(\App\Models\Address::factory()->make(['address_type' => 'Billing']));
                $customer->addresses()->save(\App\Models\Address::factory()->make(['address_type' => 'Shipping']));
            });

        // Demo Categories with real text
        $this->call(CategoryGroupsSeeder::class);

        $this->call(CategorySubGroupsSeeder::class);

        // \App\CategoryGroup::factory()->count($this->count)->create();

        \App\Models\CategorySubGroup::factory()->count($this->count)->create();

        $this->call(CategoriesSeeder::class);

        \App\Models\Category::factory()->count($this->longCount)->create();

        \App\Models\Manufacturer::factory()->create([
            'name' => 'acme', 'slug' => 'acme',
        ]);
        \App\Models\Manufacturer::factory()->count($this->count)->create();

        \App\Models\Supplier::factory()->count($this->tinycount)
            ->create()
            ->each(function ($supplier) {
                $supplier->addresses()->save(\App\Models\Address::factory()->make(['address_title' => $supplier->name, 'address_type' => 'Primary']));
            });

        $this->call(ProductsSeeder::class);

        \App\Models\AttributeValue::factory()->count($this->longCount)->create();

        \App\Models\Warehouse::factory()->count(1)->create()
            ->each(function ($warehouse) {
                $warehouse->addresses()->save(\App\Models\Address::factory()->make(['address_title' => $warehouse->name, 'address_type' => 'Primary']));
            });

        $shipping_zones = DB::table('shipping_zones')->pluck('id')->toArray();

        foreach ($shipping_zones as $zone) {
            \App\Models\ShippingRate::factory()->count($this->tinycount)->create([
                'shipping_zone_id' => $zone,
            ]);
        }

        \App\Models\Tax::factory()->count($this->tinycount)->create();

        \App\Models\Carrier::factory()->count($this->tinycount)->create();

        \App\Models\Packaging::factory()->count($this->tinycount)->create();

        $this->call(InventoriesSeeder::class);

        \App\Models\Order::factory()->count($this->count)->create();

        \App\Models\Dispute::factory()->count($this->tinycount)->create();

        $this->call(BlogSeeder::class);

        \App\Models\BlogComment::factory()->count($this->longCount)->create();

        \App\Models\Tag::factory()->count($this->longCount)->create();

        // \App\GiftCard::factory()->count($this->count)->create();

        \App\Models\Coupon::factory()->count($this->count)->create();

        \App\Models\Message::factory()->count($this->count)->create();

        \App\Models\Ticket::factory()->count($this->tinycount)->create();

        \App\Models\Reply::factory()->count($this->longCount)->create();

        //PIVOT TABLE SEEDERS
        $customers = DB::table('customers')->pluck('id')->toArray();
        // $users      = DB::table('users')->pluck('id')->toArray();
        $products = DB::table('products')->pluck('id')->toArray();
        $shops = DB::table('shops')->pluck('id')->toArray();
        // $warehouses = DB::table('warehouses')->pluck('id')->toArray();
        $categories = DB::table('categories')->pluck('id')->toArray();
        // $category_sub_groups = DB::table('category_sub_groups')->pluck('id')->toArray();
        $attributes = DB::table('attributes')->pluck('id')->toArray();
        $coupons = DB::table('coupons')->pluck('id')->toArray();
        $inventories_ids = DB::table('inventories')->pluck('id')->toArray();
        $manufacturers = DB::table('manufacturers')->pluck('id')->toArray();

        // shop_payment_methods
        $wire = DB::table('payment_methods')->where('code', 'wire')->first()->id;
        $cod = DB::table('payment_methods')->where('code', 'cod')->first()->id;
        $shop_payment_methods = [];
        $config_manual_payments = [];
        foreach ($shops as $shop) {
            $shop_payment_methods[] = [
                'shop_id' => $shop,
                'payment_method_id' => $cod,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ];
            $shop_payment_methods[] = [
                'shop_id' => $shop,
                'payment_method_id' => $wire,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ];

            $config_manual_payments[] = [
                'shop_id' => $shop,
                'payment_method_id' => $wire,
                'additional_details' => 'Send the payment via Bank Wire Transfer.',
                'payment_instructions' => 'Payment instructions for Bank Wire Transfer',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ];
            $config_manual_payments[] = [
                'shop_id' => $shop,
                'payment_method_id' => $cod,
                'additional_details' => 'Our man will collect the payment when deliver the item to your doorstep.',
                'payment_instructions' => 'Payment instructions for COD',
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ];
        }
        DB::table('shop_payment_methods')->insert($shop_payment_methods);
        DB::table('config_manual_payments')->insert($config_manual_payments);

        // attribute_inventory
        $attribute_inventory = [];
        foreach ((range(1, $this->longCount)) as $index) {
            $attribute_id = $attributes[array_rand($attributes)];
            $attribute_values = DB::table('attribute_values')->where('attribute_id', $attribute_id)->pluck('id')->toArray();
            if (empty($attribute_values)) {
                continue;
            }

            $attribute_inventory[] = [
                'attribute_id' => $attribute_id,
                'inventory_id' => $inventories_ids[array_rand($inventories_ids)],
                'attribute_value_id' => $attribute_values[array_rand($attribute_values)],
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ];
        }
        DB::table('attribute_inventory')->insert($attribute_inventory);

        // order_items
        $orders = DB::table('orders')->get();

        $order_items = [];
        foreach ($orders as $order) {
            $inventories = DB::table('inventories')->where('shop_id', $order->shop_id)->get()->toArray();
            $shipping_weight = 0;
            $item_count = 0;
            $shipping_qtt = 0;
            $total = 0;

            $temps = array_rand($inventories, rand(2, 4));

            foreach ($temps as $temp) {
                $qtt = rand(1, 3);
                $order_items[] = [
                    'order_id' => $order->id,
                    'inventory_id' => $inventories[$temp]->id,
                    'item_description' => $inventories[$temp]->title . ' - ' . $inventories[$temp]->condition,
                    'quantity' => $qtt,
                    'unit_price' => $inventories[$temp]->sale_price,
                    'created_at' => $this->now,
                    'updated_at' => $this->now,
                ];

                $item_count++;
                $shipping_qtt += $qtt;
                $shipping_weight += $inventories[$temp]->shipping_weight * $qtt;
                $total += $inventories[$temp]->sale_price * $qtt;
            }

            $shipping = rand(1, 9);
            // Update order with correct qtt and total
            DB::table('orders')->where('id', $order->id)->update([
                'item_count' => $item_count,
                'quantity' => $shipping_qtt,
                'shipping_weight' => $shipping_weight,
                'shipping' => $shipping,
                'total' => $total,
                'grand_total' => $shipping + $total,
            ]);
        }
        DB::table('order_items')->insert($order_items);

        // Blog tags
        $data = [];
        $blogs = DB::table('blogs')->pluck('id')->toArray();
        $tags = DB::table('tags')->pluck('id')->toArray();
        foreach ($blogs as $blog) {
            $z = rand(1, 7);
            for ($i = 1; $i <= $z; $i++) {
                $data[] = [
                    'tag_id' => $tags[array_rand($tags)],
                    'taggable_id' => $blog,
                    'taggable_type' => \App\Models\Blog::class,
                ];
            }
        }
        DB::table('taggables')->insert($data);

        // category_product
        $data = [];
        foreach ($products as $product) {
            for ($i = 0; $i <= rand(2, 4); $i++) {
                $data[] = [
                    'category_id' => $categories[array_rand($categories)],
                    'product_id' => $product,
                    'created_at' => $this->now,
                    'updated_at' => $this->now,
                ];
            }
        }
        DB::table('category_product')->insert($data);

        // user_warehouse
        // foreach ((range(1, $this->longCount)) as $index) {
        //     DB::table('user_warehouse')->insert(
        //         [
        //             'warehouse_id' => $warehouses[array_rand($warehouses)],
        //             'user_id' => $users[array_rand($users)],
        //             'created_at' => $this->now,
        //             'updated_at' => $this->now,
        //         ]
        //     );
        // }

        // foreach ((range(1, 30)) as $index) {
        //     DB::table('taggables')->insert(
        //         [
        //             'tag_id' => rand(1, 20),
        //             'taggable_id' => rand(1, 20),
        //             'taggable_type' => rand(0, 1) == 1 ? 'App\Post' : 'App\Video'
        //         ]
        //     );
        // }

        // coupon_customers
        foreach ((range(1, $this->count)) as $index) {
            DB::table('coupon_customer')->insert(
                [
                    'coupon_id' => $coupons[array_rand($coupons)],
                    'customer_id' => $customers[array_rand($customers)],
                    'created_at' => $this->now,
                    'updated_at' => $this->now,
                ]
            );
        }

        // Frontend Seeder

        $this->call(SlidersSeeder::class);

        $this->call(BannersSeeder::class);

        \App\Models\Wishlist::factory()->count($this->count)->create();
        \App\Models\Feedback::factory()->count($this->veryLongCount)->create();

        $this->call(EmailTemplateSeeder::class);

        // Announcement seeder
        $deals = ['**Deal** of the day', 'Fashion accessories **deals**', 'Kids item **deals**', 'Black Friday **Deals**!', 'ONLY FOR TODAY:: **80% Off!**', 'Everyday essentials **deals**', '**Save** up to 40%', '**FLASH SALE ::** 20% **Discount** only for TODAY!!!'];

        DB::table('announcements')->insert([
            'id' => '9e274a6b-1340-4862-8ca2-525331830725',
            'user_id' => 1,
            'body' => $deals[array_rand($deals)],
            'action_text' => 'Shop Now',
            'action_url' => '/',
            'created_at' => $this->now,
            'updated_at' => $this->now,
        ]);

        \App\Models\Visitor::factory()->count($this->longCount)->create();

        // options table seeder

        $taglines = ['40% off on kids item only', 'Free shipping!', 'Black Friday Offer!', '50% OFF ONLY FOR TODAY', 'Everyday essentials deals', 'Save up to 40%', 'FLASH SALE 40% Discount!'];

        $max_qtt_item = DB::table('inventories')->orderBy('stock_quantity', 'desc')->first();

        DB::table('options')->insert([
            [
                'option_name' => 'trending_categories',
                'option_value' => serialize($this->get_random_element($categories, 3)),
                'autoload' => 1,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ], [
                'option_name' => 'promotional_tagline',
                'option_value' => serialize(['text' => $taglines[array_rand($taglines)], 'action_url' => '/']),
                'autoload' => 1,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ], [
                'option_name' => 'featured_items',
                'option_value' => serialize($this->get_random_element($inventories_ids, 10)),
                'autoload' => 1,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ], [
                'option_name' => 'featured_brands',
                'option_value' => serialize($this->get_random_element($manufacturers, 3)),
                'autoload' => 1,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ], [
                'option_name' => 'deal_of_the_day',
                'option_value' => $max_qtt_item->id,
                'autoload' => 1,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ],
        ]);

        // Insert all images at once
        if (count($images_data) > 0) {
            DB::table('images')->insert($images_data);
        }

        $this->call(PostDemoSeeder::class);
    }
}
