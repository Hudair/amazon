<?php

namespace Incevio\Package\DynamicCommission\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Incevio\Package\DynamicCommission\Http\Requests\DynamicCommissionRequest;

class DynamicCommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dynamicCommission::milestones');
    }

    //Update dynamic commission
    public function updateCommission(DynamicCommissionRequest $request)
    {
        $data = [];
        foreach ($request->milestones['commissions'] as $key => $value) {
            $data[] = [
                'milestone' => $request->milestones['amounts'][$key],
                'commission' => $value
            ];
        }

        // If empty, set the default values
        if (empty($data)) {
            $data[] = ['milestone' => 0, 'commission' => 0];
        }

         DB::table(get_option_table_name())->updateOrInsert(
            ['option_name' => 'dynamicCommission_milestones'],
            [
                'option_name' => 'dynamicCommission_milestones',
                'option_value' => serialize($data),
                'updated_at' => now(),
            ]
        );

        return redirect()->route(config('dynamicCommission.routes.settings'))
        ->with('success', trans('dynamicCommission::lang.update_success'));
    }

}
