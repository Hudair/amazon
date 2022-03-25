<?php

namespace Incevio\Package\DynamicCommission;

use Illuminate\Support\Facades\DB;

class Uninstaller
{
    public $package;

    public function __construct()
    {
        $this->package = 'Dynamic commissions';
    }

    public function cleanDatabase()
    {
        \Log::info("Cleaning Seeds: " . $this->package);

        $package_data = DB::table(get_option_table_name())
            ->where('option_name', 'like', 'dynamicCommission_%');

        if ($package_data->delete()) {
            \Log::info("Cleaning successful: " . $this->package);

            return true;
        }

        \Log::info("Cleaning FAILED or No data found: " . $this->package);

        return true;
    }
}
