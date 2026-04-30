<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'userId', 'type', 'packageId', 'carId', 'startDate', 'endDate',
        'totalPrice', 'customerName', 'customerEmail', 'customerPhone',
        'notes', 'metadata', 'status', 'bookingCode',
    ];

    protected $casts = [
        'startDate' => 'datetime',
        'endDate' => 'datetime',
        'totalPrice' => 'double',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'packageId');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'carId');
    }

    // Scopes
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
