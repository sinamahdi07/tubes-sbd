<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyPlayMartEmail extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Email PlayMart')
            ->view(
                [
                    'html' => 'emails.auth.verify-email',
                    'text' => 'emails.auth.verify-email-text',
                ],
                [
                    'appName' => 'PlayMart',
                    'expireMinutes' => config('auth.verification.expire', 60),
                    'url' => $verificationUrl,
                    'user' => $notifiable,
                ]
            );
    }
}
