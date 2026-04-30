<?php

namespace App\Repositories;

use App\Models\Booking;

class BookingRepository
{
    public function getAll(array $filters = [])
    {
        $query = Booking::with(['package', 'car']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('bookingCode', 'like', "%{$filters['search']}%")
                  ->orWhere('customerName', 'like', "%{$filters['search']}%")
                  ->orWhere('customerEmail', 'like', "%{$filters['search']}%");
            });
        }

        return $query->latest('createdAt')->paginate($filters['per_page'] ?? 15);
    }

    public function find(int $id)
    {
        return Booking::with(['package', 'car'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Booking::create($data);
    }

    public function update(Booking $booking, array $data)
    {
        $booking->update($data);
        return $booking->fresh();
    }

    public function delete(Booking $booking)
    {
        return $booking->delete();
    }
}
