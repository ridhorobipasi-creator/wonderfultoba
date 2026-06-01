<?php

namespace App\Models;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin \Eloquent
 */

class Client extends Model
{
    use Syncable;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'name', 'logo', 'websiteUrl', 'orderPriority', 'isActive',
    ];

    protected $casts = [
        'isActive' => 'boolean',
    ];
}
