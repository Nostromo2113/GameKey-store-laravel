<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected string $mailableClass, // Класс письма (ActivationKey, PasswordReset и т.п.)
        protected array $mailData,       // Переменные для разметки письма
        protected string $toEmail        // email получателя
    ) {

    }

    public function handle()
    {
        $mailable = new $this->mailableClass($this->mailData); // Сюда можно прокидывать экземпляры, без прямого вызова
        Mail::to($this->toEmail)->send($mailable); // Отправка письма
    }
}
