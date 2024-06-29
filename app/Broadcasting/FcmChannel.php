<?php

namespace App\Broadcasting;

use App\Models\User;

use Illuminate\Notifications\Notification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class FcmChannel
{
    /**
     * Create a new channel instance.
     */
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));
        $this->messaging = $factory->createMessaging();
    }

    public function send($notifiable, Notification $notification)
    {
        if (! $token = $notifiable->routeNotificationFor('fcm', $notification)) {
            return;
        }

        $message = $notification->toFcm($notifiable);

        $this->messaging->send($message);
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user): array|bool
    {
        //
    }
}
