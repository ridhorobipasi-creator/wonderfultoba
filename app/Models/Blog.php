<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'slug', 'title', 'content', 'excerpt', 'image', 'author', 'category', 'status',
    ];
}
