<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(BannerGroupsSeeder::class);
        $this->call(TimezonesSeeder::class);
        $this->call(CurrenciesSeeder::class);
        $this->call(CountriesSeeder::class);
        $this->call(StatesSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(SystemsSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(ModulesSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(AttributeSeeder::class);
        $this->call(GtinSeeder::class);
        $this->call(PaymentMethodsSeeder::class);
        $this->call(AddressTypesSeeder::class);
        $this->call(TicketCategoriesSeeder::class);
        $this->call(DisputeTypesSeeder::class);
        $this->call(TaxesSeeder::class);
        $this->call(PackagingsSeeder::class);
        $this->call(SubscriptionPlansSeeder::class);
        $this->call(PagesSeeder::class);
        $this->call(FaqsSeeder::class);
        $this->call(LanguagesSeeder::class);
        $this->call(CancellationReasonSeeder::class);
        //$this->call(DeliverBoySeeder::class);
        // $this->call(AssignDeliveryBoyOrderSeeder::class);
        // $this->call(OptionTableSeeder::class);
        // $this->call(demoSeeder::class);
        $this->command->info('Seeding complete!');

        Model::reguard();
    }
}
