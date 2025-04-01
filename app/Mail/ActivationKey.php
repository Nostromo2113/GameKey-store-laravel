<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivationKey extends Mailable
{
    use Queueable, SerializesModels;
    public $activationKeys;

    /**
     * Create a new message instance.
     */
    public function __construct($activationKeys)
    {
        $this->activationKeys = $activationKeys;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.activationKey')
            ->subject('Ваши ключи активации');
    }
}
