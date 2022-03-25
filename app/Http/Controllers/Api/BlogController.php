<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $blogs = Blog::recent()->published()->with(['image', 'author:id,name,nice_name', 'tags'])
        ->withCount('comments')->paginate(config('mobile_app.view_listing_per_page', 8));

        return BlogResource::collection($blogs);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {
        $blog = Blog::where('slug', $slug)->published()->withCount('comments')
        ->with('publishedComments', 'publishedComments.author')->firstOrFail();

        return new BlogResource($blog);
    }
}
