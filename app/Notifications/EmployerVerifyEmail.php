<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class EmployerVerifyEmail extends VerifyEmail
{
    /**
     * @param object $notifiable
     */
    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'employer.verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * @param object $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Потврдете ја е-поштата за вашата компанија')
            ->greeting('Здраво!')
            ->line('Ви благодариме за регистрацијата на Honorarec.mk.')
            ->line('За да ја активирате компанијата и employer пристапот, потврдете ја вашата е-пошта преку копчето подолу.')
            ->action('Потврди е-пошта', $verificationUrl)
            ->line('Ако не ја направивте оваа регистрација, слободно игнорирајте ја оваа порака.')
            ->salutation('Поздрав, Honorarec.mk');
    }
}
