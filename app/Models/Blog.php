<?php

namespace App\Models;

use App\Traits\HasImageFallback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use \App\Traits\Syncable, HasImageFallback, SoftDeletes;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';
/**
 * @mixin \Eloquent
 */

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
