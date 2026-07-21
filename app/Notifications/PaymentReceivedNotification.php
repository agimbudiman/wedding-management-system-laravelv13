<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentReceivedNotification extends Notification
{
    use Queueable;
    public $payment;
    
    public function __construct($payment) { $this->payment = $payment; }
    public function via($notifiable) { return ['database']; }
    public function toDatabase($notifiable) {
        return [
            'title' => 'New Payment Received',
            'message' => 'Payment received for ' . ($this->payment->event ? $this->payment->event->name : 'an event') . ' (Rp ' . number_format($this->payment->amount, 0, ',', '.') . ').',
            'url' => route('management.payment.show', $this->payment->id)
        ];
    }
}
