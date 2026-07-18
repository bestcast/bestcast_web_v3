<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BmpEventService;

class RetryBmpEvents extends Command
{
    protected $signature = 'bmp:retry-events';
    protected $description = 'Retry any pending or failed BMP referral events';

    public function handle()
    {
        $count = BmpEventService::retryPending();
        $this->info("Processed {$count} pending/failed BMP events.");
    }
}