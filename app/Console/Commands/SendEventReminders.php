<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Event;
use Carbon\Carbon;
use App\Notifications\EventReminderNotification;

#[Signature('app:send-event-reminders')]
#[Description('Sends event reminders to assigned crew members at H-7, H-3, H-1, and D-Day.')]
class SendEventReminders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        $intervals = [7, 3, 1, 0];
        
        foreach ($intervals as $days) {
            $targetDate = $today->copy()->addDays($days);
            
            $events = Event::with('crews')
                ->whereDate('date', $targetDate)
                ->get();
                
            foreach ($events as $event) {
                foreach ($event->crews as $crew) {
                    $crew->notify(new EventReminderNotification($event, $days));
                }
            }
        }

        $this->info('Event reminders sent successfully.');
    }
}
