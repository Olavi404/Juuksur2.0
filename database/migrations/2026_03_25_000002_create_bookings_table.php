<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('hairdresser_id')->constrained()->cascadeOnDelete();
            $table->date('booking_date');
            $table->time('start_time');
            $table->string('customer_name', 120);
            $table->string('customer_email')->nullable();
            $table->string('customer_phone', 40);
            $table->timestamps();

            $table->unique(['hairdresser_id', 'booking_date', 'start_time'], 'bookings_unique_slot');
            $table->index('booking_date');
            $table->index(['hairdresser_id', 'booking_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
