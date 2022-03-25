<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Inventory;
use App\Common\Authorizable;
use App\Helpers\ListHelper;
use App\Http\Controllers\Controller;
use App\Repositories\Inventory\InventoryRepository;
use App\Http\Requests\Validations\AddInventoryRequest;
use App\Http\Requests\Validations\CreateInventoryRequest;
use App\Http\Requests\Validations\UpdateInventoryRequest;
use App\Http\Requests\Validations\CreateInventoryWithVariantRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class InventoryController extends Controller
{
    use Authorizable;

    private $model;

    private $inventory;

    /**
     * construct
     */
    public function __construct(InventoryRepository $inventory)
    {
        parent::__construct();

        $this->model = trans('app.model.inventory');

        $this->inventory = $inventory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trashes = $this->inventory->trashOnly();

        return view('admin.inventory.index', compact('trashes'));
    }

    // Function will process the ajax request to fetch data
    public function getInventory(Request $request, $status = 'active')
    {
        $inventory = Inventory::with('product', 'image');

        if (!Auth::user()->isFromPlatform()) {
            $inventory = $inventory->mine();
        }

        if ($status == 'active') {
            $inventory = $inventory->active();
        } elseif ($status == 'inactive') {
            $inventory = $inventory->inActive();
        } elseif ($status == 'outOfStock') {
            $inventory = $inventory->stockOut();
        }

        $data = Datatables::of($inventory)
            ->editColumn('checkbox', function ($inventory) {
                return view('admin.inventory.partials.checkbox', compact('inventory'));
            })
            ->addColumn('option', function ($inventory) {
                return view('admin.inventory.partials.options', compact('inventory'));
            })
            ->editColumn('image', function ($inventory) {
                return view('admin.inventory.partials.image', compact('inventory'));
            })
            ->editColumn('sku', function ($inventory) {
                return view('admin.inventory.partials.sku', compact('inventory'));
            })
            ->editColumn('title', function ($inventory) {
                return view('admin.inventory.partials.title', compact('inventory'));
            })
            ->editColumn('condition', function ($inventory) {
                return view('admin.inventory.partials.condition', compact('inventory'));
            })
            ->editColumn('sale_price', function ($inventory) {
                return view('admin.inventory.partials.price', compact('inventory'));
            })
            ->editColumn('quantity', function ($inventory) {
                return view('admin.inventory.partials.quantity', compact('inventory'));
            });

        $rawColumns = ['image', 'sku', 'title', 'sale_price', 'quantity', 'checkbox', 'option'];

        if (config('system_settings.show_item_conditions')) {
            $data = $data->editColumn('condition', function ($inventory) {
                return view('admin.inventory.partials.condition', compact('inventory'));
            });

            $rawColumns[] = 'condition';
        }

        return $data->rawColumns($rawColumns)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setVariant(AddInventoryRequest $request, Product $product)
    {
        $attributes = ListHelper::getAttributesBy($product);

        return view('admin.inventory._set_variant', compact('product', 'attributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(AddInventoryRequest $request, $id)
    {
        if (!$request->user()->shop->canAddMoreInventory()) {
            return redirect()->route('admin.stock.inventory.index')
                ->with('error', trans('messages.cant_add_more_inventory'));
        }

        $inInventory = $this->inventory->checkInventoryExist($id);

        if ($inInventory) {
            return redirect()->route('admin.stock.inventory.edit', $inInventory->id)
                ->with('warning', trans('messages.inventory_exist'));
        }

        $product = Product::with('categories.attrsList.attributeValues')->findOrFail($id);

        $attributes = ListHelper::getAttributesBy($product);

        return view('admin.inventory.create', compact('product', 'attributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addWithVariant(AddInventoryRequest $request, $id)
    {
        if (!$request->user()->shop->canAddMoreInventory()) {
            return redirect()->route('admin.stock.inventory.index')
                ->with('error', trans('messages.cant_add_more_inventory'));
        }

        $variants = $this->inventory->confirmAttributes($request->except('_token'));

        $combinations = generate_combinations($variants);

        $attributes = $this->inventory->getAttributeList(array_keys($variants));

        $product = $this->inventory->findProduct($id);

        return view('admin.inventory.createWithVariant', compact('combinations', 'attributes', 'product'));
    }

    /**
     * Add a product to inventory.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateInventoryRequest $request)
    {
        $this->authorize('create', \App\Models\Inventory::class); // Check permission

        $inventory = $this->inventory->store($request);

        $request->session()->flash('success', trans('messages.created', ['model' => $this->model]));

        return response()->json($this->getJsonParams($inventory));
    }

    /**
     * Add inventory with variants.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeWithVariant(CreateInventoryWithVariantRequest $request)
    {
        $this->inventory->storeWithVariant($request);

        return redirect()->route('admin.stock.inventory.index')
            ->with('success', trans('messages.created', ['model' => $this->model]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inventory = $this->inventory->find($id);

        return view('admin.inventory._show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inventory = $this->inventory->find($id);

        $product = $this->inventory->findProduct($inventory->product_id);

        $preview = $inventory->previewImages();

        $attributes = ListHelper::getAttributesBy($product);

        return view('admin.inventory.edit', compact('inventory', 'product', 'preview', 'attributes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editQtt($id)
    {
        $inventory = $this->inventory->find($id);

        $this->authorize('update', $inventory); // Check permission

        return view('admin.inventory._editQtt', compact('inventory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInventoryRequest $request, $id)
    {
        $inventory = $this->inventory->update($request, $id);

        // For inspectable
        if (!Auth::user()->isFromPlatform()) {
            $this->authorize('update', $inventory); // Check permission
        }

        $request->session()->flash('success', trans('messages.updated', ['model' => $this->model]));

        return response()->json($this->getJsonParams($inventory));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateQtt(Request $request, $id)
    {
        $inventory = $this->inventory->updateQtt($request, $id);

        return response('success', 200);
    }

    /**
     * Trash the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, $id)
    {
        $this->inventory->trash($id);

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
        $this->inventory->restore($id);

        return back()->with('success', trans('messages.restored', ['model' => $this->model]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->inventory->destroy($id);

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
        $this->inventory->massTrash($request->ids);

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
    public function massDestroy(Request $request)
    {
        $this->inventory->massDestroy($request->ids);

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
        $this->inventory->emptyTrash($request);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model]));
    }

    /**
     * return json params to procceed the form
     *
     * @param  Product $product
     *
     * @return array
     */
    private function getJsonParams($inventory)
    {
        $route = Auth::user()->isFromPlatform() ? 'admin.inspector.inspectables' : 'admin.stock.inventory.index';

        return [
            'id' => $inventory->id,
            'model' => 'inventory',
            'redirect' => route($route),
        ];
    }
}
