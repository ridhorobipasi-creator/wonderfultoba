<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutboundLocation extends Model
{
    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = ['name', 'image'];
}
