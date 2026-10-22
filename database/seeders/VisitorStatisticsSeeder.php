<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VisitorStatistic;
use Carbon\Carbon;

class VisitorStatisticsSeeder extends Seeder
{
    public function run(): void
    {
        if (VisitorStatistic::count() > 0) {
            return;
        }

        $now = Carbon::now();

        // Daily records (last 14 days)
        for ($i = 13; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            VisitorStatistic::create([
                'date' => $date->format('Y-m-d'),
                'count' => rand(180, 480),
                'period_type' => 'daily',
                'notes' => 'Pengunjung Harian',
            ]);
        }

        // Weekly records (last 8 weeks)
        for ($w = 7; $w >= 1; $w--) {
            $date = $now->copy()->subWeeks($w)->startOfWeek();
            VisitorStatistic::create([
                'date' => $date->format('Y-m-d'),
                'count' => rand(1500, 3200),
                'period_type' => 'weekly',
                'notes' => 'Ringkasan Mingguan',
            ]);
        }

        // Monthly records (last 12 months)
        for ($m = 11; $m >= 0; $m--) {
            $date = $now->copy()->subMonths($m)->startOfMonth();
            VisitorStatistic::create([
                'date' => $date->format('Y-m-d'),
                'count' => rand(6000, 14000),
                'period_type' => 'monthly',
                'notes' => 'Ringkasan Bulanan',
            ]);
        }

        // Yearly records (last 4 years)
        for ($y = 3; $y >= 0; $y--) {
            $date = $now->copy()->subYears($y)->startOfYear();
            VisitorStatistic::create([
                'date' => $date->format('Y-m-d'),
                'count' => rand(65000, 110000),
                'period_type' => 'yearly',
                'notes' => 'Ringkasan Tahunan',
            ]);
        }
    }
}
