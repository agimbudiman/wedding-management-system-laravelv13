<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TodoCompletedNotification extends Notification
{
    use Queueable;
    public $todo;
    public $event;
    
    public function __construct($todo, $event) { $this->todo = $todo; $this->event = $event; }
    public function via($notifiable) { return ['database']; }
    public function toDatabase($notifiable) {
        return [
            'title' => 'Task Completed',
            'message' => 'Task "' . ($this->todo->title ?? $this->todo->task ?? 'Unknown') . '" for ' . $this->event->name . ' has been completed by ' . ($this->todo->assignedTo ? $this->todo->assignedTo->name : 'a crew member') . '.',
            'url' => route('management.event.show', $this->event->id) . '#todo'
        ];
    }
}
