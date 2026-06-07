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

    /**
     * Label setiap status (urutan = alur pipeline Kanban).
     */
    public const STATUS_LABELS = [
        'pending' => 'Inquiry Baru',
        'follow_up' => 'Follow Up',
        'confirmed' => 'DP Masuk',
        'on_tour' => 'Sedang Tour',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    /**
     * Status yang dianggap menghasilkan pendapatan (sudah ada pembayaran/berjalan/selesai).
     */
    public const REVENUE_STATUSES = ['confirmed', 'on_tour', 'completed'];

    /**
     * Daftar nilai status yang valid (untuk validasi).
     */
    public static function statusKeys(): array
    {
        return array_keys(self::STATUS_LABELS);
    }

    /**
     * Aturan validasi 'in:...' untuk status.
     */
    public static function statusRule(): string
    {
        return 'in:'.implode(',', self::statusKeys());
    }

    /**
     * Metadata kolom Kanban: key, label, dan warna aksen.
     */
    public static function kanbanColumns(): array
    {
        return [
            'pending' => ['label' => 'Inquiry Baru', 'color' => 'slate', 'icon' => 'fa-inbox'],
            'follow_up' => ['label' => 'Follow Up', 'color' => 'amber', 'icon' => 'fa-comments'],
            'confirmed' => ['label' => 'DP Masuk', 'color' => 'blue', 'icon' => 'fa-hand-holding-dollar'],
            'on_tour' => ['label' => 'Sedang Tour', 'color' => 'violet', 'icon' => 'fa-route'],
            'completed' => ['label' => 'Selesai', 'color' => 'emerald', 'icon' => 'fa-circle-check'],
            'cancelled' => ['label' => 'Dibatalkan', 'color' => 'rose', 'icon' => 'fa-ban'],
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst((string) $this->status);
    }

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
