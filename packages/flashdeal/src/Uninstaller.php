<?php

namespace Incevio\Package\Flashdeal;

use Illuminate\Support\Facades\DB;

class Uninstaller
{
    public $package;

    public function __construct()
    {
        $this->package = 'Flashdeal';
    }

	public function cleanDatabase()
	{
        \Log::info("Cleaning Seeds: " . $this->package);

        if(DB::table(get_option_table_name())->where('option_name', 'like', 'flashdeal_%')->delete()) {
	        \Log::info("Cleaning successfull: " . $this->package);
        }
        else {
	        \Log::info("Cleaning FAILED: " . $this->package);
        }

		return true;
	}
}