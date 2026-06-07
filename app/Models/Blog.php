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

    protected $fillable = [
        'slug', 'title', 'content', 'excerpt', 'image', 'author', 'category', 'status', 'tags',
    ];

    protected $appends = ['image_url'];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    public function getImageUrlAttribute()
    {
        return $this->resolveImageUrl($this->image);
    }
}
