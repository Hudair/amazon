<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EloquentProduct extends EloquentRepository implements BaseRepository, ProductRepository
{
    protected $model;

    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function all()
    {
        if (Auth::user()->isFromPlatform()) {
            return $this->model->with('categories', 'shop.logo', 'featuredImage', 'image')
                ->withCount('inventories')->get();
        }

        return $this->model->mine()->with('categories', 'featuredImage', 'image')
            ->withCount('inventories')->get();
    }

    public function find($id)
    {
        return $this->model->with([
            // 'inventories' => function ($q) {
            //     $q->available();
            // },
            // 'inventories',
            'inventories.shop',
        ])->find($id);
    }

    public function trashOnly()
    {
        if (Auth::user()->isFromPlatform()) {
            return $this->model->onlyTrashed()->with('categories', 'featuredImage')->get();
        }

        return $this->model->mine()->onlyTrashed()->with('categories', 'featuredImage')->get();
    }

    public function store(Request $request)
    {
        $product = parent::store($request);

        if ($request->input('category_list')) {
            $product->categories()->sync($request->input('category_list'));
        }

        if ($request->input('tag_list')) {
            $product->syncTags($product, $request->input('tag_list'));
        }

        return $product;
    }

    public function update(Request $request, $id)
    {
        $product = parent::update($request, $id);

        $product->categories()->sync($request->input('category_list', []));

        $product->syncTags($product, $request->input('tag_list', []));

        return $product;
    }

    public function destroy($product)
    {
        if (!$product instanceof Product) {
            $product = parent::findTrash($product);
        }

        $product->detachTags($product->id, 'product');

        $product->flushImages();

        if ($product->hasFeedbacks()) {
            $product->flushFeedbacks();
        }

        return $product->forceDelete();
    }

    public function massDestroy($ids)
    {
        $products = Product::onlyTrashed()->whereIn('id', $ids)->get();

        foreach ($products as $product) {
            $product->detachTags($product->id, 'product');

            $product->flushImages();

            if ($product->hasFeedbacks()) {
                $product->flushFeedbacks();
            }
        }

        return parent::massDestroy($ids);
    }

    public function emptyTrash()
    {
        $products = Product::onlyTrashed()->get();

        foreach ($products as $product) {
            $product->detachTags($product->id, 'product');

            $product->flushImages();

            if ($product->hasFeedbacks()) {
                $product->flushFeedbacks();
            }
        }

        return parent::emptyTrash();
    }
}
