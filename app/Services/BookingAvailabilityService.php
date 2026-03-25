<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

class BookingAvailabilityService
{
    /**
     * @return list<string>
     */
    public function workingSlots(): array
    {
        return [
            '09:00',
            '10:00',
            '11:00',
            '12:00',
            '13:00',
            '14:00',
            '15:00',
            '16:00',
        ];
    }

    /**
     * @return list<string>
     */
    public function availableSlots(int $hairdresserId, string $bookingDate): array
    {
        $normalizedDate = Carbon::parse($bookingDate)->toDateString();

        $booked = Booking::query()
            ->where('hairdresser_id', $hairdresserId)
            ->whereDate('booking_date', $normalizedDate)
            ->pluck('start_time')
            ->map(static fn (string $time): string => Carbon::parse($time)->format('H:i'))
            ->all();

        return array_values(array_diff($this->workingSlots(), $booked));
    }
}
