<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\CategorySubGroup;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryGroupResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategorySubGroupResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $sub_group = null)
    {
        $categories = Category::active();

        if ($sub_group) {
            $categories = $categories->where('category_sub_group_id', $sub_group);
        }

        $categories = $categories->with(['coverImage', 'coverImage'])
            ->orderBy('order', 'asc')->get();

        return CategoryResource::collection($categories);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryGroup()
    {
        $categories = CategoryGroup::with(['coverImage'])
            ->orderBy('order', 'asc')
            ->active()->get();

        return CategoryGroupResource::collection($categories);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categorySubGroup(Request $request, $group = null)
    {
        $categories = CategorySubGroup::active();

        if ($group) {
            $categories = $categories->where('category_group_id', $group);
        }

        $categories = $categories->with(['coverImage'])
            ->orderBy('order', 'asc')
            ->get();

        return CategorySubGroupResource::collection($categories);
    }

    public function featuredCategories()
    {
        $categories = get_featured_category();

        return CategoryResource::collection($categories);
    }

    public function trendingCategories()
    {
        $categories = get_trending_categories();

        return CategoryResource::collection($categories);
    }
}
