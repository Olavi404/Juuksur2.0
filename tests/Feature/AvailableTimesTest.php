<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Hairdresser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailableTimesTest extends TestCase
{
    use RefreshDatabase;

    public function test_available_times_exclude_booked_slots(): void
    {
        $hairdresser = Hairdresser::query()->create(['name' => 'Test Stylist']);

        Booking::query()->create([
            'hairdresser_id' => $hairdresser->id,
            'booking_date' => '2026-04-01',
            'start_time' => '10:00',
            'customer_name' => 'Booked Person',
            'customer_email' => 'booked@example.com',
            'customer_phone' => '123456',
        ]);

        $response = $this->getJson('/available-times?hairdresser_id=' . $hairdresser->id . '&booking_date=2026-04-01');

        $response->assertOk();
        $response->assertJsonMissing(['10:00']);
        $response->assertJsonFragment(['slots' => ['09:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00']]);
    }
}
