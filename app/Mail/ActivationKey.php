<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivationKey extends Mailable
{
    use Queueable;
    use SerializesModels;
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
