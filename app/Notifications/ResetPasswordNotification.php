<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    /**
     * Build the reset URL using the user's org subdomain, not APP_URL.
     * This ensures the link works correctly across subdomains.
     */
    protected function resetUrl($notifiable): string
    {
        // Try to load the org from the user's organization_id
        $org = $notifiable->organization_id
            ? \App\Models\Organization::withoutGlobalScopes()->find($notifiable->organization_id)
            : null;

        $base = $org ? $org->subdomainUrl() : config('app.url');

        return $base . '/reset-password/' . $this->token . '?' . http_build_query([
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset Your Password — ' . config('app.name'))
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $this->resetUrl($notifiable))
            ->line('This password reset link will expire in ' . config('auth.passwords.users.expire') . ' minutes.')
            ->line('If you did not request a password reset, no further action is required.');
    }
}
