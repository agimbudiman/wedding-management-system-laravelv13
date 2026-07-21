<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventReminderNotification extends Notification
{
    use Queueable;
    public $event;
    public $daysLeft;
    
    public function __construct($event, $daysLeft) { $this->event = $event; $this->daysLeft = $daysLeft; }
    public function via($notifiable) { return ['database']; }
    public function toDatabase($notifiable) {
        $timeString = $this->daysLeft == 0 ? 'is today!' : 'is in ' . $this->daysLeft . ' day(s).';
        return [
            'title' => 'Event Reminder',
            'message' => 'Event ' . $this->event->name . ' ' . $timeString,
            'url' => route('management.event.show', $this->event->id)
        ];
    }
}
