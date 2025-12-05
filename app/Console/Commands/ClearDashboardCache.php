<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearDashboardCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear dashboard cache untuk refresh data statistik';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cacheKeys = [
            'dashboard.stats',
            'dashboard.status_stats',
            'dashboard.recent_activities',
            'dashboard.activity_counts',
            'dashboard.kantor_by_kota',
            'dashboard.gedung_by_kantor',
            'dashboard.okupansi_by_bidang',
            'dashboard.kontrak_by_status',
            'dashboard.kontrak_by_month',
            'dashboard.top_kantor',
            'dashboard.top_bidang',
            'dashboard.analytics',
            'dashboard.kantor_map',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        $this->info('Dashboard cache cleared successfully!');
        return 0;
    }
}

