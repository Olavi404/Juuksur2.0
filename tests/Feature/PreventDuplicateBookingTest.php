<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Hairdresser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreventDuplicateBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_booking_for_same_slot_is_rejected(): void
    {
        $hairdresser = Hairdresser::query()->create(['name' => 'Tester']);

        Booking::query()->create([
            'hairdresser_id' => $hairdresser->id,
            'booking_date' => '2026-04-01',
            'start_time' => '09:00',
            'customer_name' => 'Existing',
            'customer_email' => 'existing@example.com',
            'customer_phone' => '555',
        ]);

        $response = $this->from('/')
            ->post('/bookings', [
                'hairdresser_id' => $hairdresser->id,
                'booking_date' => '2026-04-01',
                'start_time' => '09:00',
                'customer_name' => 'New Customer',
                'customer_email' => 'new@example.com',
                'customer_phone' => '999',
            ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'Sorry, this time slot was just booked. Please choose another time.');
    }
}
