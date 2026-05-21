<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasImageFallback;

class Blog extends Model
{
    use HasImageFallback, SoftDeletes, \App\Traits\Syncable;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'slug', 'title', 'content', 'excerpt', 'image', 'author', 'category', 'status', 'tags',
    ];

    protected $appends = ['image_url', 'translated_title', 'translated_excerpt', 'translated_category'];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    public function getImageUrlAttribute()
    {
        return $this->resolveImageUrl($this->image);
    }

    public function getTranslatedTitleAttribute()
    {
        return __($this->title);
    }

    public function getTranslatedExcerptAttribute()
    {
        return __($this->excerpt);
    }

    public function getTranslatedCategoryAttribute()
    {
        return __($this->category);
    }
}
