<?php

namespace App\Http\Controllers\Admin;

use App\Models\DeliveryBoy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CreateDeliveryBoyRequest;
use App\Http\Requests\Validations\UpdateDeliveryBoyRequest;
use App\Repositories\DeliveryBoy\DeliveryBoyRepository;
use Illuminate\Http\Request;

class DeliveryBoyController extends Controller
{
    private $model_name;

    private $deliveryBoy;

    /**
     * construct
     */
    public function __construct(DeliveryBoyRepository $deliveryBoy)
    {
        parent::__construct();

        $this->model_name = trans('app.model.delivery_boy');
        $this->deliveryBoy = $deliveryBoy;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveryBoys = $this->deliveryBoy->all();

        $trashes = $this->deliveryBoy->trashOnly();

        return view('admin.deliveryboy.index', compact('deliveryBoys', 'trashes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.deliveryboy._create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDeliveryBoyRequest $request)
    {
        $this->deliveryBoy->store($request);

        return back()->with('success', trans('messages.created', ['model' => $this->model_name]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deliveryboy = $this->deliveryBoy->find($id);

        return view('admin.deliveryboy._show', compact('deliveryboy'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliveryBoy $deliveryboy)
    {
        return view('admin.deliveryboy._edit', compact('deliveryboy'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDeliveryBoyRequest $request, DeliveryBoy $deliveryboy)
    {
        if (config('app.demo') == true && $deliveryboy->id <= config('system.demo.delivery_boys')) {
            return response()->json(['message' => trans('messages.demo_restriction')], 400);
        }

        $this->deliveryBoy->update($request, $deliveryboy->id);

        return back()->with('success', trans('messages.created', ['model' => $this->model_name]));
    }

    /**
     * Permanently Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->deliveryBoy->destroy($id);

        return back()->with('success', trans('messages.deleted', ['model' => $this->model_name]));
    }

    /**
     * Move items from storage to trash
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.delivery_boys')) {
            return response()->json(['message' => trans('messages.demo_restriction')], 400);
        }

        $this->deliveryBoy->trash($id);

        return back()->with('success', trans('messages.trashed', ['model' => $this->model_name]));
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
        $this->deliveryBoy->restore($id);

        return back()->with('success', trans('messages.restored', ['model' => $this->model_name]));
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

        $this->deliveryBoy->massTrash($request->ids);

        if ($request->ajax()) {
            return response()->json([
                'success' => trans('messages.trashed', ['model' => $this->model_name])
            ]);
        }

        return back()->with('success', trans('messages.trashed', ['model' => $this->model_name]));
    }

    /**
     * Destroy the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request)
    {
        if (config('app.demo') == true) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->deliveryBoy->massDestroy($request->ids);

        if ($request->ajax()) {
            return response()->json([
                'success' => trans('messages.deleted', ['model' => $this->model_name])
            ]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model_name]));
    }

    /**
     * Destroy the mass trash resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyTrash(Request $request)
    {
        if (config('app.demo') == true) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->deliveryBoy->emptyTrash($request->ids);

        if ($request->ajax()) {
            return response()->json([
                'success' => trans('messages.deleted', ['model' => $this->model_name])
            ]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model_name]));
    }
}
