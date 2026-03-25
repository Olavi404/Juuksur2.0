<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class PublicBookingPageTest extends TestCase
{
    public function test_public_booking_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Book an Appointment');
    }
}
