<?php

namespace App\Models;

use App\Common\Imageable;
use App\Common\Taggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends BaseModel
{
    use HasFactory, SoftDeletes, Imageable, Taggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'blogs';

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'published_at',
        'user_id',
        'views',
        'likes',
        'dislikes',
        'status',
    ];

    /**
     * Get the author associated with the blog post.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    /**
     * Get the comments for the blog post.
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function publishedComments()
    {
        return $this->hasMany(BlogComment::class)->published();
    }

    /**
     * Scope a query to only include published blogs.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where([
                ['status', '=', 1],
                ['approved', '=', 1],
                ['published_at', '<=', Carbon::now()],
            ]);
    }

    /**
     * Scope a query to most polular blogs.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePopular($query)
    {
        return $query->published()->orderBy('likes', 'DESC');
    }

    /**
     * Scope a query to most polular blogs.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'DESC');
    }

    /**
     * Set tag time formate for the post.
     *
     * @return array
     */
    public function setPublishedAtAttribute($value)
    {
        if ($value) {
            $this->attributes['published_at'] = Carbon::createFromFormat('Y-m-d h:i a', $value);
        }
    }

    /**
     * Getters
     */
    public function getViewsAttribute($value)
    {
        return $value ?? rand(1, 1000); // TEMPORARY! Will be added to database in 2.1 version
    }
}
