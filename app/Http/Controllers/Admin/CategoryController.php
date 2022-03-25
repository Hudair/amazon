<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Helpers\ListHelper;
use App\Common\Authorizable;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Category\CategoryRepository;
use App\Http\Requests\Validations\CreateCategoryRequest;
use App\Http\Requests\Validations\UpdateCategoryRequest;

class CategoryController extends Controller
{
    use Authorizable;

    private $model_name;

    private $category;

    /**
     * construct
     */
    public function __construct(CategoryRepository $category)
    {
        parent::__construct();
        $this->model_name = trans('app.model.category');
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $categories = $this->category->all();

        $trashes = $this->category->trashOnly();

        return view('admin.category.index', compact('trashes'));
    }

    // Function will process the ajax request to fetch data
    public function getCategories(Request $request)
    {
        $category = Category::with(
            'subGroup:id,name,category_group_id,deleted_at',
            'subGroup.group:id,name,deleted_at',
            'featureImage',
            'coverImage'
        )->withCount(['products', 'listings', 'attrsList']);

        $data = Datatables::of($category)
            ->editColumn('checkbox', function ($category) {
                return view('admin.category.partials.checkbox', compact('category'));
            })
            ->addColumn('option', function ($category) {
                return view('admin.category.partials.options', compact('category'));
            })
            ->editColumn('cover_image', function ($category) {
                return view('admin.category.partials.cover_image', compact('category'));
            })
            ->editColumn('feature_image', function ($category) {
                return view('admin.category.partials.feature_image', compact('category'));
            })
            ->editColumn('name', function ($category) {
                return view('admin.category.partials.name', compact('category'));
            })
            ->editColumn('parent', function ($category) {
                return view('admin.category.partials.parent', compact('category'));
            })
            ->editColumn('attrs_list_count', function ($category) {
                return view('admin.category.partials.attributes', compact('category'));
            })
            ->editColumn('listings_count', function ($category) {
                return view('admin.category.partials.listings', compact('category'));
            })
            ->editColumn('products_count', function ($category) {
                return view('admin.category.partials.products', compact('category'));
            });

        $rawColumns = ['cover_image', 'feature_image', 'name', 'attrsList_count', 'listings_count', 'products_count', 'checkbox', 'option'];

        return $data->rawColumns($rawColumns)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category._create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $category = $this->category->store($request);

        DB::transaction(function () use ($category, $request) {
            $category->attrsList()->sync($request->attrsList);
        });

        return back()->with('success', trans('messages.created', ['model' => $this->model_name]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = $this->category->find($id);

        return view('admin.category._edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = $this->category->update($request, $id);

        $this->treat_cache($request, $id);

        DB::transaction(function () use ($category, $request) {
            $category->attrsList()->sync($request->attrsList);
        });

        return back()->with('success', trans('messages.updated', ['model' => $this->model_name]));
    }

    /**
     * Trash the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, $id)
    {
        // Check for association with products
        if ($this->category->find($id)->products->count()) {
            $notice = trans('messages.model_has_association', ['model' => $this->model_name, 'associate' => trans('app.products')]);

            return back()->with('error', trans('messages.failed'))->with('global_notice', $notice);
        }

        $this->category->trash($id);

        $this->treat_cache($request, $id);

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
        $this->category->restore($id);

        $this->treat_cache($request, $id);

        return back()->with('success', trans('messages.restored', ['model' => $this->model_name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->category->destroy($id);

        $this->treat_cache($request, $id);

        return back()->with('success', trans('messages.deleted', ['model' => $this->model_name]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massTrash(Request $request)
    {
        $this->category->massTrash($request->ids);

        $this->treat_cache($request, $request->ids);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.trashed', ['model' => $this->model_name])]);
        }

        return back()->with('success', trans('messages.trashed', ['model' => $this->model_name]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request)
    {
        $this->category->massDestroy($request->ids);

        $this->treat_cache($request, $request->ids);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model_name])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model_name]));
    }

    /**
     * Empty the Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyTrash(Request $request)
    {
        $this->category->emptyTrash($request);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model_name])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model_name]));
    }

    /**
     * Clear the cache when needed
     */
    private function treat_cache($request, $id)
    {
        $ids = is_array($id) ? $id : [$id];

        if (
            Cache::has('featured_categories') &&
            !empty(array_intersect(Cache::get('featured_categories')->pluck('id')->toArray(), $ids))
        ) {
            // Clear featured_categories from cache
            Cache::forget('featured_categories');
        }
    }
}
