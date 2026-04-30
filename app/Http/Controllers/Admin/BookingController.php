<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    public function index(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'type' => $request->get('type'),
            'search' => $request->get('search'),
            'date' => $request->get('date'),
            'month' => $request->get('month'),
            'year' => $request->get('year'),
            'per_page' => $request->get('per_page', 15),
        ];

        $bookings = $this->bookingService->getAll($filters);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        return view('admin.bookings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:package,car',
            'packageId' => 'nullable|exists:packages,id',
            'carId' => 'nullable|exists:cars,id',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email',
            'customerPhone' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $booking = $this->bookingService->create($validated);

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Booking created successfully');
    }

    public function show(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        return view('admin.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email',
            'customerPhone' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $this->bookingService->update($booking, $validated);

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Booking updated successfully');
    }

    public function destroy(Booking $booking)
    {
        $this->bookingService->delete($booking);

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $this->bookingService->updateStatus($booking, $validated['status']);

        return back()->with('success', 'Booking status updated successfully');
    }
}
