<?php

namespace App\Notifications;

use App\Mail\ResetPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /* public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("restablecimiento")
            ->greeting(Lang::get('Hello') . " {$notifiable->name},")
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::get('Reset Password'), $this->resetUrl($notifiable))
            ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(Lang::get('If you did not request a password reset, no further action is required.'));
    }*/
    public function toMail($notifiable)
    {
        $resetUrl = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Restablecimiento de contraseña')
            ->view('emails.reset-password', [
                'url' => $resetUrl,
                'user' => $notifiable,
                'count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire'),
            ]);
    }


    protected function resetUrl(mixed $notifiable): string
    {
        return Filament::getResetPasswordUrl($this->token, $notifiable);
    }
}
