<?php

use App\Jobs\FetchAssetIconsJob;
use App\Jobs\FetchAssetsJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('logs:clear', function() {
    exec('rm -f ' . storage_path('logs/*.log'));
    exec('rm -f ' . base_path('*.log'));
    $this->comment('Logs have been cleared!');
})->describe('Clear log files');

Schedule::job(FetchAssetsJob::class)->everyThreeMinutes();
Schedule::job(FetchAssetIconsJob::class)->everyThreeMinutes();
