<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class addedNotification extends Notification
{
    use Queueable;


    private $messageData;
    public function __construct($messageData)
    {
        $this->messageData = $messageData;
    }



     public function via($notifiable)
     {
           return ['database'];
     }

     public function toArray($notifiable)
     {

         return [
           'message' => $this->messageData
         ];
     }

}
