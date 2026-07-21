<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MidtransOrderPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $event;
    public $clientEmail;
    public $clientWhatsapp;

    /**
     * Create a new message instance.
     */
    public function __construct($payment, $clientEmail = null, $clientWhatsapp = null)
    {
        $this->payment = $payment;
        $this->event = $payment ? $payment->event : null;
        $this->clientEmail = $clientEmail;
        $this->clientWhatsapp = $clientWhatsapp;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Wedding System: Reservasi Berhasil & Pembayaran DP Diterima!')
                    ->view('emails.order_paid_email');
    }
}
