<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Hairdresser;
use App\Services\BookingAvailabilityService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingAvailabilityService $availabilityService,
    ) {
    }

    public function create(): View
    {
        return view('bookings.create', [
            'hairdressers' => Hairdresser::query()->orderBy('name')->get(),
            'workingSlots' => $this->availabilityService->workingSlots(),
        ]);
    }

    public function availableTimes(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'hairdresser_id' => ['required', 'integer', 'exists:hairdressers,id'],
            'booking_date' => ['required', 'date'],
        ]);

        $slots = $this->availabilityService->availableSlots(
            (int) $validated['hairdresser_id'],
            (string) $validated['booking_date'],
        );

        return response()->json([
            'slots' => $slots,
        ]);
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $available = $this->availabilityService->availableSlots(
            (int) $validated['hairdresser_id'],
            (string) $validated['booking_date'],
        );

        if (!in_array($validated['start_time'], $available, true)) {
            return back()
                ->withInput()
                ->with('error', 'Sorry, this time slot was just booked. Please choose another time.');
        }

        try {
            Booking::query()->create($validated);
        } catch (QueryException $exception) {
            // PostgreSQL unique violation SQLSTATE.
            if ($exception->getCode() === '23505') {
                return back()
                    ->withInput()
                    ->with('error', 'Sorry, this time slot was just booked. Please choose another time.');
            }

            throw $exception;
        }

        return redirect()
            ->route('bookings.create')
            ->with('success', 'Booking created successfully.');
    }
}
