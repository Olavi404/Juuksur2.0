<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Hairdresser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_can_be_created_with_valid_data(): void
    {
        $hairdresser = Hairdresser::query()->create(['name' => 'Tester']);

        $response = $this->post('/bookings', [
            'hairdresser_id' => $hairdresser->id,
            'booking_date' => '2026-04-01',
            'start_time' => '09:00',
            'customer_name' => 'Alice',
            'customer_email' => 'alice@example.com',
            'customer_phone' => '555-0000',
        ]);

        $response->assertRedirect(route('bookings.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'hairdresser_id' => $hairdresser->id,
            'booking_date' => '2026-04-01',
            'start_time' => '09:00:00',
            'customer_name' => 'Alice',
        ]);
    }
}
