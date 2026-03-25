@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h1 class="h4 mb-0">Book an Appointment</h1>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="hairdresser_id" class="form-label">Hairdresser</label>
                            <select name="hairdresser_id" id="hairdresser_id" class="form-select" required>
                                <option value="">Select hairdresser</option>
                                @foreach ($hairdressers as $hairdresser)
                                    <option value="{{ $hairdresser->id }}" @selected((string) old('hairdresser_id') === (string) $hairdresser->id)>
                                        {{ $hairdresser->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="booking_date" class="form-label">Date</label>
                            <input
                                type="date"
                                name="booking_date"
                                id="booking_date"
                                class="form-control"
                                value="{{ old('booking_date') }}"
                                min="{{ now()->toDateString() }}"
                                required
                            >
                        </div>

                        <div class="col-md-12">
                            <label for="start_time" class="form-label">Available Time</label>
                            <select name="start_time" id="start_time" class="form-select" required>
                                <option value="">Select hairdresser and date first</option>
                            </select>
                            <small class="text-muted">Working hours: 09:00 to 16:00 (1 hour slots)</small>
                        </div>

                        <div class="col-md-6">
                            <label for="customer_name" class="form-label">Your Name</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control" maxlength="120" value="{{ old('customer_name') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="customer_phone" class="form-label">Phone</label>
                            <input type="text" name="customer_phone" id="customer_phone" class="form-control" maxlength="40" value="{{ old('customer_phone') }}" required>
                        </div>

                        <div class="col-md-12">
                            <label for="customer_email" class="form-label">Email (optional)</label>
                            <input type="email" name="customer_email" id="customer_email" class="form-control" maxlength="255" value="{{ old('customer_email') }}">
                        </div>
                    </div>

                    <div class="mt-4 d-grid">
                        <button type="submit" class="btn btn-primary">Create Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const hairdresserSelect = document.getElementById('hairdresser_id');
    const dateInput = document.getElementById('booking_date');
    const slotSelect = document.getElementById('start_time');

    async function refreshAvailableTimes() {
        const hairdresserId = hairdresserSelect.value;
        const bookingDate = dateInput.value;

        if (!hairdresserId || !bookingDate) {
            slotSelect.innerHTML = '<option value="">Select hairdresser and date first</option>';
            return;
        }

        slotSelect.innerHTML = '<option value="">Loading available times...</option>';

        const params = new URLSearchParams({
            hairdresser_id: hairdresserId,
            booking_date: bookingDate,
        });

        try {
            const response = await fetch(`{{ route('bookings.available-times') }}?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            const slots = data.slots ?? [];

            if (slots.length === 0) {
                slotSelect.innerHTML = '<option value="">No available times</option>';
                return;
            }

            const oldValue = @json(old('start_time'));
            slotSelect.innerHTML = '<option value="">Select a time</option>';

            for (const slot of slots) {
                const option = document.createElement('option');
                option.value = slot;
                option.textContent = slot;

                if (oldValue && oldValue === slot) {
                    option.selected = true;
                }

                slotSelect.appendChild(option);
            }
        } catch (error) {
            slotSelect.innerHTML = '<option value="">Failed to load times</option>';
        }
    }

    hairdresserSelect.addEventListener('change', refreshAvailableTimes);
    dateInput.addEventListener('change', refreshAvailableTimes);

    if (hairdresserSelect.value && dateInput.value) {
        refreshAvailableTimes();
    }
</script>
@endpush
