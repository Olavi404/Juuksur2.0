<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Hairdresser;
use Illuminate\Database\Seeder;

class HairdresserSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Emma Johnson',
            'Olivia Brown',
            'Sophia Davis',
        ];

        foreach ($names as $name) {
            Hairdresser::query()->firstOrCreate(['name' => $name]);
        }
    }
}
