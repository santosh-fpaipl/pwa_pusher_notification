<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Services\WebPushMessage;
use App\Services\WebPushChannel;

class WebPushNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Hello '.$notifiable->name)
            ->icon('/notification-icon.png')
            ->body('Good, Push Notifications work!')
            ->action('View App', 'notification_action');
    }

    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         'test' => now()
    //     ];
    // }
}