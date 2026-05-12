<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FineSetting;

class FineSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key'         => 'daily_fine_amount',
                'value'       => '2000',
                'description' => 'Denda per hari keterlambatan (Rupiah)',
            ],
            [
                'key'         => 'max_borrow_days',
                'value'       => '7',
                'description' => 'Maksimal hari peminjaman',
            ],
            [
                'key'         => 'auto_cancel_hours',
                'value'       => '24',
                'description' => 'Jam otomatis cancel jika tidak diambil',
            ],
        ];

        foreach ($settings as $setting) {
            FineSetting::create($setting);
        }
    }
}