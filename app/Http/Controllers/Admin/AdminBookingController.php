<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBookingController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date'],
        ]);

        $bookings = Booking::query()
            ->with('hairdresser')
            ->when(
                !empty($validated['date']),
                fn ($query) => $query->whereDate('booking_date', $validated['date'])
            )
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->paginate(20)
            ->withQueryString();

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'selectedDate' => $validated['date'] ?? '',
        ]);
    }
}
