<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Common\Authorizable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\CreateProductRequest;
use App\Http\Requests\Validations\UpdateProductRequest;
use App\Repositories\Product\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

class ProductController extends Controller
{
    use Authorizable;

    private $model;

    private $product;

    /**
     * construct
     */
    public function __construct(ProductRepository $product)
    {
        parent::__construct();

        $this->model = trans('app.model.product');

        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trashes = $this->product->trashOnly();

        return view('admin.product.index', compact('trashes'));
    }

    // function will process the ajax request
    public function getProducts(Request $request)
    {
        // $products = Product::select('*');
        $products = Product::with('categories', 'shop.logo', 'featureImage', 'image')
            ->withCount('inventories');

        // When accessing by a merchent user
        if (Auth::user()->isFromMerchant()) {
            $products->mine();
        }

        return Datatables::of($products)
            ->editColumn('checkbox', function ($product) {
                return view('admin.partials.actions.product.checkbox', compact('product'));
            })
            ->addColumn('option', function ($product) {
                return view('admin.partials.actions.product.options', compact('product'));
            })
            ->editColumn('image', function ($product) {
                return view('admin.partials.actions.product.image', compact('product'));
            })
            ->editColumn('name', function ($product) {
                return view('admin.partials.actions.product.name', compact('product'));
            })
            ->editColumn('gtin', function ($product) {
                return view('admin.partials.actions.product.gtin', compact('product'));
            })
            ->editColumn('category', function ($product) {
                return view('admin.partials.actions.product.category', compact('product'));
            })
            ->editColumn('inventories_count', function ($product) {
                return view('admin.partials.actions.product.inventories_count', compact('product'));
            })
            ->editColumn('added_by', function ($product) {
                return view('admin.partials.actions.product.added_by', compact('product'));
            })
            ->rawColumns(['image', 'name', 'gtin', 'category', 'inventories_count', 'added_by', 'status', 'checkbox', 'option'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $this->authorize('create', Product::class); // Check permission

        $product = $this->product->store($request);

        $request->session()->flash('success', trans('messages.created', ['model' => $this->model]));

        return response()->json($this->getJsonParams($product));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->product->find($id);

        $this->authorize('view', $product); // Check permission

        return view('admin.product._show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $this->product->find($id);

        $this->authorize('update', $product); // Check permission

        $preview = $product->previewImages();

        return view('admin.product.edit', compact('product', 'preview'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = $this->product->update($request, $id);

        $this->authorize('update', $product); // Check permission

        $request->session()->flash('success', trans('messages.updated', ['model' => $this->model]));

        return response()->json($this->getJsonParams($product));
    }

    /**
     * Trash the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, $id)
    {
        $this->product->trash($id);

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
        $this->product->restore($id);

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
        $this->product->destroy($id);

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
        $this->product->massTrash($request->ids);

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
        $this->product->massRestore($request->ids);

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
        $this->product->massDestroy($request->ids);

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
        $this->product->emptyTrash($request);

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
    private function getJsonParams($product)
    {
        return [
            'id' => $product->id,
            'model' => 'product',
            'redirect' => route('admin.catalog.product.index'),
        ];
    }
}
