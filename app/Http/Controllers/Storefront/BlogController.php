<?php

namespace App\Http\Controllers\Storefront;

use App\Models\Blog;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::select(['id', 'title', 'slug', 'excerpt', 'user_id', 'published_at', 'likes', 'dislikes'])
            ->published()->recent()
            ->with('author:id,name,nice_name', 'tags')
            ->withCount('comments')
            ->paginate(config('system.view_blog_post_per_page', 4));

        $tags = $this->getTags($blogs);

        return view('theme::blog', compact('blogs', 'tags'));
    }

    /**
     * Show the specified resource.
     *
     * @param  \App\slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {
        $blog = Blog::where('slug', $slug)->published()->firstOrFail();

        return view('theme::blog', compact('blog'));
    }

    /**
     * All post by this author
     *
     * @param  Request $request
     * @param  User    $author
     *
     * @return \Illuminate\Http\Response
     */
    public function author(Request $request, $author)
    {
        $blogs = Blog::select(['id', 'title', 'slug', 'excerpt', 'user_id', 'published_at', 'likes', 'dislikes'])
            ->where('user_id', $author)
            ->with('author:id,name,nice_name')
            ->withCount('comments')
            ->paginate(config('system.view_blog_post_per_page', 4));

        $tags = $this->getTags($blogs);

        return view('theme::blog', compact('blogs', 'tags'));
    }

    /**
     * Search the specified resource.
     *
     * @param  \App\slug  $slug
     * @return \Illuminate\Http\Response
     */
    // public function search(Request $request, $q = null)
    public function search(Request $request)
    {
        $blogs = Blog::select(['id', 'title', 'slug', 'excerpt', 'user_id', 'published_at', 'likes', 'dislikes'])
            ->where('title', 'LIKE', '%'.$request->q.'%')
            ->orWhere('content', 'LIKE', '%'.$request->q.'%')
            ->with('author:id,name,nice_name')
            ->withCount('comments')
            ->paginate(config('system.view_blog_post_per_page', 4));

        $tags = $this->getTags($blogs);

        return view('theme::blog', compact('blogs', 'tags'));
    }

    /**
     * Search the specified resource.
     *
     * @param  \App\slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function tag(Request $request, $tag)
    {
        $blogs = Blog::select(['id', 'title', 'slug', 'excerpt', 'user_id', 'published_at', 'likes', 'dislikes'])
            ->whereHas('tags', function ($query) use ($tag) {
                $query->whereName($tag);
            })
            ->published()
            ->with('author:id,name,nice_name', 'tags')
            ->withCount('comments')
            ->paginate(config('system.view_blog_post_per_page', 4));

        $tags = $this->getTags($blogs);

        return view('theme::blog', compact('blogs', 'tags'));
    }

    /**
     * Return flaten array of tags
     *
     * @param  arr $blogs
     *
     * @return arr
     */
    private function getTags($blogs)
    {
        $tags = array_filter($blogs->pluck('tags')->toArray());

        return $tags ? array_merge(...$tags) : [];
    }
}
