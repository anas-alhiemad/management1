<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class BeneficiaryAddedNotification extends Notification
{
    use Queueable;


    private $requestPending;
    private $name;

    public function __construct($requestPending,$name)
    {
        $this->requestPending = $requestPending;
        $this->name = $name;

    }



     public function via($notifiable)
     {
           return ['database','fcm'];
     }

     public function toArray($notifiable)
     {

         return [
           'message' => 'New requestPending Added',
           'Hello i am'. $this->name = $name.
           'I will added the student is name:'. $this->requestPending->name
            // 'approve_url' => Http::post(('/api/admin/requests/' . $this->request->id . '/approve')),
            // 'reject_url' => url('/api/admin/requests/' . $this->request->id . '/reject'),


         ];
     }




     public function toFcm($notifiable)
     {
        // $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));
        $factory = (new Factory)->withServiceAccount('services.firebase.credentials');
        $messaging = $factory->createMessaging();

         $message = CloudMessage::withTarget('token', $notifiable->fcm_token)
             ->withNotification(FcmNotification::create('New requestPending Added', "Hello i am". $this->name = $name. ' I will added the student is name: ' . $this->requestPending->name));

         $messaging->send($message);
     }



    //  public function toBroadcast($notifiable)
    //  {
    //      return new BroadcastMessage([
    //          'message' => 'A new student request has been submitted.',
    //          'request_id' => $this->beneficiaryrequest->id,
    //          'student_name' => $this->beneficiaryrequest->student_name,
    //      ]);
    //  }

    //  public function via(object $notifiable): array
    // {
    //     return ['mail'];
    // }

    // /**
    //  * Get the mail representation of the notification.
    //  */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         //
    //     ];
//    }
}
