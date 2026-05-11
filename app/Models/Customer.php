<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'email', 'phone', 'address', 'notes',
        'total_bookings', 'total_spent', 'last_booking_at'
    ];

    protected $casts = [
        'last_booking_at' => 'datetime',
        'total_spent' => 'decimal:2',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customerId');
    }
}
