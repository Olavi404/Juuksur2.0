<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_page_redirects_guest_to_login(): void
    {
        $response = $this->get('/admin/bookings');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_admin_can_see_bookings_list(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'password',
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin/bookings');

        $response->assertOk();
        $response->assertSee('Admin Bookings');
    }
}
