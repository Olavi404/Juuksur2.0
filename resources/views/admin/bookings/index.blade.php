@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Admin Bookings</h1>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="date" class="form-label">Filter by date</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ $selectedDate }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Booking Date</th>
                    <th>Start Time</th>
                    <th>Hairdresser</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td>{{ $booking->booking_date->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</td>
                        <td>{{ $booking->hairdresser->name }}</td>
                        <td>{{ $booking->customer_name }}</td>
                        <td>{{ $booking->customer_email ?? '-' }}</td>
                        <td>{{ $booking->customer_phone }}</td>
                        <td>{{ $booking->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No bookings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-white">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
