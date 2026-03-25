<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'hairdresser_id',
        'booking_date',
        'start_time',
        'customer_name',
        'customer_email',
        'customer_phone',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'start_time' => 'datetime:H:i',
        ];
    }

    public function hairdresser(): BelongsTo
    {
        return $this->belongsTo(Hairdresser::class);
    }
}
