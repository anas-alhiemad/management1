<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BeneficiaryAddedNotification extends Notification
{
    use Queueable;


    private $requestPending;

    public function __construct($requestPending)
    {
        $this->requestPending = $requestPending;


    }



     public function via($notifiable)
     {
           return ['database'];
     }

     public function toArray($notifiable)
     {

         return [
           'message' => <<<"dataNOtification"
            Hello,There is a pending request to add a student.
            Please review it when you find yourself having time
            dataNOtification,

            // 'approve_url' => Http::post(('/api/admin/requests/' . $this->request->id . '/approve')),
            // 'reject_url' => url('/api/admin/requests/' . $this->request->id . '/reject'),


         ];
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
