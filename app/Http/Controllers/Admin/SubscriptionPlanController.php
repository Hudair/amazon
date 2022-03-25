<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop;
use App\Models\SubscriptionPlan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CreateSubscriptionPlanRequest;
use App\Http\Requests\Validations\UpdateSubscriptionPlanRequest;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    private $model;

    /**
     * construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->model = trans('app.model.subscription_plan');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscription_plans = SubscriptionPlan::withCount('shops')->orderBy('order', 'asc')->get();

        $trashes = SubscriptionPlan::onlyTrashed()->get();

        return view('admin.subscription_plan.index', compact('subscription_plans', 'trashes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.subscription_plan._create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSubscriptionPlanRequest $request)
    {
        $plan = new SubscriptionPlan;

        $plan->fill($request->all())->save();

        return back()->with('success', trans('messages.created', ['model' => $this->model]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function show(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscription_plan._show', compact('subscriptionPlan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscription_plan._edit', compact('subscriptionPlan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubscriptionPlanRequest $request, SubscriptionPlan $subscriptionPlan)
    {
        if (
            config('app.demo') == true &&
            in_array($subscriptionPlan->plan_id, config('system.demo.plans', ['business', 'individual', 'professional']))
        ) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        try {
            \DB::beginTransaction();

            // If the plan_id changed. Update all shops that under this plan
            if ($subscriptionPlan->plan_id !== $request->plan_id) {
                Shop::where('current_billing_plan', $subscriptionPlan->plan_id)
                    ->update(['current_billing_plan' => $request->plan_id]);
            }

            $subscriptionPlan->update($request->all());

            \DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return back()->with('success', trans('messages.updated', ['model' => $this->model]));
    }

    /**
     * Trash the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubscriptionPlan  $subscriptionPlan
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        if (
            config('app.demo') == true &&
            in_array($subscriptionPlan->plan_id, config('system.demo.plans', ['business', 'individual', 'professional']))
        ) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $subscriptionPlan->delete();

        return back()->with('success', trans('messages.trashed', ['model' => $this->model]));
    }

    /**
     * Restore the specified resource from soft delete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        SubscriptionPlan::onlyTrashed()->findOrFail($id)->restore();

        return back()->with('success', trans('messages.restored', ['model' => $this->model]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SubscriptionPlan::onlyTrashed()->findOrFail($id)->forceDelete();

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massTrash(Request $request)
    {
        if (config('app.demo') == true) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        SubscriptionPlan::whereIn('plan_id', $request->ids)->delete();

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.trashed', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.trashed', ['model' => $this->model]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massRestore(Request $request)
    {
        SubscriptionPlan::onlyTrashed()->whereIn('plan_id', $request->ids)->restore();

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.restored', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.restored', ['model' => $this->model]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request)
    {
        if (config('app.demo') == true) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        SubscriptionPlan::withTrashed()->whereIn('plan_id', $request->ids)->forceDelete();

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }

    /**
     * Empty the Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyTrash(Request $request)
    {
        SubscriptionPlan::onlyTrashed()->forceDelete();

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }

    /**
     * Save sorting order for attributes by ajax
     */
    public function reorder(Request $request)
    {
        // $this->attribute->reorder($request->all());

        foreach ($request->all() as $id => $order) {
            SubscriptionPlan::findOrFail($id)->update(['order' => $order]);
        }

        return response('success!', 200);
    }
}
