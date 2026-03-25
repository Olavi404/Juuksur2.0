<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Services\BookingAvailabilityService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $slots = app(BookingAvailabilityService::class)->workingSlots();

        return [
            'hairdresser_id' => ['required', 'integer', 'exists:hairdressers,id'],
            'booking_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i', Rule::in($slots)],
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:40'],
        ];
    }
}
