<?php

namespace App\Http\Controllers\Admin;

use App\Models\System;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class Incevio extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // Restricted on demo
        if (config('app.demo') == true) {
            echo trans('messages.demo_restriction');
            exit();
        }
    }

    /**
     * Check different type system information
     */
    public function check($option = 'version')
    {
        if ($option == 'geoip' || $option == 'ip') {
            return geoip(get_visitor_IP())->toArray();
        }

        return '<h1 style="margin-top:100px; text-align: center;">You\'re markerplace running on zCart version: ' . System::VERSION . '</h1>';
    }

    /**
     * New version upgrade
     */
    public function upgrade($option = 'migrate')
    {
        // Universal upgrading process
        Artisan::call('migrate');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('incevio:clear-cache');

        // Upgrading from 2.3 to 2.4
        if (\App\Models\System::VERSION == '2.4.0') {
            // Artisan::call('cashier:webhook --disabled');

            $morphs = [
                'addressable_type' => 'addresses',
                'causer_type' => 'activity_log',
                'subject_type' => 'activity_log',
                'attachable_type' => 'attachments',
                'feedbackable_type' => 'avg_feedback',
                'feedbackable_type' => 'feedbacks',
                'imageable_type' => 'images',
                'repliable_type' => 'replies',
                'payable_type' => 'transactions',
                'holder_type' => 'wallets',
                'from_type' => 'transfers',
                'to_type' => 'transfers',
                // 'taggable_type' => 'taggables',
            ];

            foreach ($morphs as $column => $table) {
                // If table not exist
                if (!Schema::hasTable($table)) continue;

                if (Schema::hasColumn($table, $column)) {
                    DB::table($table)->select('id', $column)
                        ->chunkById(100, function ($records) use ($table, $column) {
                            Log::info('Updating ' . Str::upper($table) . ' table');

                            foreach ($records as $row) {
                                if (
                                    $row->$column &&
                                    $model = $this->tempGetModelName($row->$column)
                                ) {
                                    DB::table($table)->where('id', $row->id)
                                        ->update([$column => $model]);
                                }
                            }
                        });

                    Log::info(Str::upper($table) . ' table updated successfully!');
                }
            }

            // Updating Taggables
            $rows = DB::table('taggables')->get();
            Log::info('Updating Taggables table');
            foreach ($rows as $row) {
                if ($model = $this->tempGetModelName($row->taggable_type)) {
                    DB::table('taggables')
                        ->where('tag_id', $row->tag_id)
                        ->where('taggable_id', $row->taggable_id)
                        ->update(['taggable_type' => $model]);
                }
            }
            Log::info('Taggables table updated successfully!');
        }

        return '<info>✔</info> ' . Artisan::output() . '<br/>';
    }

    /**
     * Run Artisan command
     */
    public function command($option = 'job')
    {
        if ($option == 'job') {
            Artisan::call('queue:work');

            return '<info>✔</info> ' . Artisan::output() . '<br/>';
        }

        return 'Invalid command!';
    }

    /**
     * Clear config. cache etc
     */
    public function clear($all = false)
    {
        Artisan::call('optimize:clear');
        $out = '<info>✔</info> ' . Artisan::output() . '<br/><br/>';

        if ($all) {
            Artisan::call('incevio:clear-cache');
            $out .= '<info>✔</info> ' . Artisan::output() . '<br/><br/>';

            Artisan::call('cache:clear');
            $out .= '<info>✔</info> ' . Artisan::output() . '<br/><br/>';
        }

        Artisan::call('incevio:boost');
        $out .= Artisan::output() . '<br/><br/>';

        return $out . '<h3 style="text-align: center;"><a href="' . url()->previous() . '">' . trans('app.back') . '</a></h3>';
    }

    public function tempGetModelName(string $var)
    {
        $arr = explode('\\', $var);

        if (count($arr) == 2) {
            return $arr[0] . '\\Models\\' . $arr[1];
        }

        return null;
    }
    /**
     * Re index scout indexing
     */
    // public function scout($model = Null)
    // {
    //     if (! $model) {
    //         return trans('app.which_model_you_want_to_reindex');
    //     }

    //     $out = '';

    //     if ($model == 'products' || $model == 'all') {
    //         Artisan::call('scout:import "App\Product"');
    //         $out .= '<info>✔</info> '. Artisan::output() .'<br/>';
    //     }

    //     if ($model == 'inventories' || $model == 'all') {
    //         Artisan::call('scout:import "App\Inventory"');
    //         $out .= '<info>✔</info> '. Artisan::output() .'<br/>';
    //     }

    //     if ($model == 'customers' || $model == 'all') {
    //         Artisan::call('scout:import "App\Customer"');
    //         $out .= '<info>✔</info> '. Artisan::output() .'<br/>';
    //     }

    //    return $out;
    // }
}
