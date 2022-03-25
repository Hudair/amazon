<?php

namespace Incevio\Package\Inspector\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Incevio\Package\Inspector\Jobs\AfterInspectionJob;
use Incevio\Package\Inspector\Models\InspectorModel;
use Incevio\Package\Inspector\Services\InspectorService;
use Incevio\Package\Inspector\Notifications\ApproveInspected;
use Incevio\Package\Inspector\Notifications\DenyInspected;

class InspectorController extends Controller
{

    /**
     * Inspected Items
     */
    public function inspectables()
    {
        $inspectables = InspectorModel::all();

        return view('inspector::inspectables', compact('inspectables'));

    }

    /**
     * Approve Inspected Items
     */
    public function approveItem(Request $request, $id)
    {
        $className = $request->get('className');
        $class = new $className();
        $inspector = new InspectorService($class::find($id));
        $inspector->approveInspectedItem($id);

        // Dispatch the after inspection Job
        AfterInspectionJob::dispatch($inspector, ApproveInspected::class);

        return redirect()->back()->with('success', trans('inspector::lang.msg.approve_success'));

    }

    /**
     * Block Inspected Item
     */
    public function blockItem(Request $request, $id)
    {
        $className = $request->get('className');
        $class = new $className();
        $inspector = new InspectorService($class::find($id));
        $inspector->blockInspectedItem($id);
        // Dispatch the after inspection Job
        AfterInspectionJob::dispatch($inspector, DenyInspected::class);

        return redirect()->back()->with('success', trans('inspector::lang.msg.deny_success'));

    }


}
