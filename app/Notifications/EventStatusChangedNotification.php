<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventStatusChangedNotification extends Notification
{
    use Queueable;
    public $event;
    
    public function __construct($event) { $this->event = $event; }
    public function via($notifiable) { return ['database']; }
    public function toDatabase($notifiable) {
        return [
            'title' => 'Event Status Changed',
            'message' => 'Event ' . $this->event->name . ' status changed to ' . $this->event->status . '.',
            'url' => route('management.event.show', $this->event->id)
        ];
    }
}
