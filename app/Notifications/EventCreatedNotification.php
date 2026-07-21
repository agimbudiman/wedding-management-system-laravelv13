<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventCreatedNotification extends Notification
{
    use Queueable;
    public $event;
    
    public function __construct($event) { $this->event = $event; }
    public function via($notifiable) { return ['database']; }
    public function toDatabase($notifiable) {
        return [
            'title' => 'New Event Created',
            'message' => 'A new event ' . $this->event->name . ' has been added.',
            'url' => route('management.event.show', $this->event->id)
        ];
    }
}
