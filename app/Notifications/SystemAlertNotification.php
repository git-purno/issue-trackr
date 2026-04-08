<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemAlertNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $title,
        private readonly string $message,
        private readonly ?string $url = null,
        private readonly string $category = 'update',
        private readonly ?string $notificationKey = null,
        private readonly ?string $targetType = null,
        private readonly ?int $targetId = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'category' => $this->category,
            'notification_key' => $this->notificationKey,
            'target_type' => $this->targetType,
            'target_id' => $this->targetId,
        ];
    }
}
