<?php

namespace Incevio\Package\Subscription;

use Illuminate\Support\Facades\DB;

class Uninstaller
{
    public $package;

    public function __construct()
    {
        $this->package = 'Subscription';
    }

	public function cleanDatabase()
	{
        \Log::info("Cleaning Seeds: " . $this->package);
        \Log::info("Nothing to clean");

		return true;
	}
}