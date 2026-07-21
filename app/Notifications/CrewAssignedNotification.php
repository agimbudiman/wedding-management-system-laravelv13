<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CrewAssignedNotification extends Notification
{
    use Queueable;
    public $event;
    public $role;
    
    public function __construct($event, $role) { $this->event = $event; $this->role = $role; }
    public function via($notifiable) { return ['database']; }
    public function toDatabase($notifiable) {
        return [
            'title' => 'Assigned to Event',
            'message' => 'You have been assigned as ' . $this->role . ' for ' . $this->event->name . '.',
            'url' => route('management.event.show', $this->event->id) . '#crew'
        ];
    }
}
