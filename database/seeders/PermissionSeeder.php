<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::Now();
        $modules = DB::table('modules')->where('access', '!=', 'Super Admin')->get();

        foreach ($modules as $module) {
            $actions = explode(',', $module->actions);

            foreach ($actions as $action) {
                $slug = strtolower($action).'_'.Str::snake($module->name);

                if (! DB::table('permissions')->where('slug', $slug)->first()) {
                    DB::table('permissions')->insert(
                        [
                            'module_id' => $module->id,
                            'name' => Str::title($action),
                            'slug' => $slug,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    );
                }
            }
        }

        // Demo permission_role on pivot table
        $permissions = DB::table('permissions')->pluck('module_id', 'id');

        foreach ($permissions as $permission_id => $module_id) {
            $module = DB::table('modules')->where('id', $module_id)->first();

            if ($module->access != 'Merchant') {
                if (! DB::table('permission_role')->where([
                    ['permission_id', '=', $permission_id],
                    ['role_id', '=', \App\Models\Role::ADMIN],
                ])->first()) {
                    DB::table('permission_role')->insert([
                        'permission_id' => $permission_id,
                        'role_id' => \App\Models\Role::ADMIN,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            if ($module->access != 'Platform') {
                if (! DB::table('permission_role')->where([
                    ['permission_id', '=', $permission_id],
                    ['role_id', '=', \App\Models\Role::MERCHANT],
                ])->first()) {
                    DB::table('permission_role')->insert([
                        'permission_id' => $permission_id,
                        'role_id' => \App\Models\Role::MERCHANT,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }
}
