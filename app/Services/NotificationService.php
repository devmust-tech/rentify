<?php

namespace App\Services;

use App\Enums\NotificationType;
use App\Mail\SystemNotification;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send an in-app notification to a user, optionally with email.
     */
    public function notify(
        User $user,
        NotificationType $type,
        string $subject,
        string $message,
        bool $sendEmail = true,
        ?string $actionUrl = null,
        ?string $actionText = null,
    ): Notification {
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'subject' => $subject,
            'message' => $message,
            'sent_at' => now(),
        ]);

        if ($sendEmail) {
            Mail::to($user->email)->queue(
                new SystemNotification(
                    subject: $subject,
                    messageBody: $message,
                    actionUrl: $actionUrl,
                    actionText: $actionText,
                )
            );
        }

        return $notification;
    }

    /**
     * Send an in-app notification to multiple users at once.
     */
    public function notifyMany(
        Collection $users,
        NotificationType $type,
        string $subject,
        string $message,
        bool $sendEmail = true,
        ?string $actionUrl = null,
        ?string $actionText = null,
    ): void {
        foreach ($users as $user) {
            $this->notify($user, $type, $subject, $message, $sendEmail, $actionUrl, $actionText);
        }
    }
}
