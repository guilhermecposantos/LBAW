<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use App\Models\Event;
class Kernel extends ConsoleKernel
{
    
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function() {
            Event::where('eventdatetime', '<', now())
            ->where('status', '=' , 'future' )
            ->update(['status' => "ongoing"]);
        })->everyMinute();
        
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}