<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TodoAssignedNotification extends Notification
{
    use Queueable;
    public $todo;
    public $event;
    
    public function __construct($todo, $event) { $this->todo = $todo; $this->event = $event; }
    public function via($notifiable) { return ['database']; }
    public function toDatabase($notifiable) {
        return [
            'title' => 'New Task Assigned',
            'message' => 'Task "' . ($this->todo->title ?? $this->todo->task ?? 'Unknown') . '" assigned to you for ' . $this->event->name . '.',
            'url' => route('management.event.show', $this->event->id) . '#todo'
        ];
    }
}
