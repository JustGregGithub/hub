<?php

namespace App\Console;

use App\Models\Ticket;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
           $tickets = Ticket::where('status', '!=', Ticket::STATUSES['Closed'])->get();
            foreach ($tickets as $ticket) {
                //if the ticket is not updated for 1 day, or more, it will be set to Low
                if ($ticket->updated_at->diffInDays(now()) >= 3 && $ticket->updated_at->diffInDays(now()) < 4) {
                    if ($ticket->priority != Ticket::PRIORITIES['Low']) {
                        $ticket->priority = Ticket::PRIORITIES['Low'];
                        $ticket->save();
                    }
                }

                //if the ticket is not updated for 4 days, or more, it will be set to Medium
                if ($ticket->updated_at->diffInDays(now()) >= 7 && $ticket->updated_at->diffInDays(now()) < 5) {
                    if ($ticket->priority != Ticket::PRIORITIES['Medium']) {
                        $ticket->priority = Ticket::PRIORITIES['Medium'];
                        $ticket->save();
                    }
                }

                //if the ticket is not updated for 14 days, or more, it will be set to High
                if ($ticket->updated_at->diffInDays(now()) >= 14) {
                    if ($ticket->priority != Ticket::PRIORITIES['High']) {
                        $ticket->priority = Ticket::PRIORITIES['High'];
                        $ticket->save();
                    }
                }
            }

        })->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
