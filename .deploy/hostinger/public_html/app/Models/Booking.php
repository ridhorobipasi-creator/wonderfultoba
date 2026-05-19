<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Booking extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->customerEmail;
    }
    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'userId', 'customerId', 'type', 'packageId', 'startDate', 'endDate',
        'totalPrice', 'total_cost', 'customerName', 'customerEmail', 'customerPhone',
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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerId');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'packageId');
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
