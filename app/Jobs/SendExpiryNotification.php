<?php

namespace App\Jobs;

use App\Models\Item;
use App\Models\User;
use App\Http\Controllers\NotificationController;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendExpiryNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $item;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::where('role', 'warehouseguard')->first();

        if ($user) {
            $notificationController = new NotificationController();
            $notificationController->sendFCMNotification(
                $user->id,
                "Item Expiry Alert",
                "Item ({$this->item->name}) is expiring soon on {$this->item->expired_date}. Please take necessary actions."
            );

            // Update the item to mark it as notified
            $this->item->notified_for_expiry = true;
            $this->item->save();
        }
    }
}
