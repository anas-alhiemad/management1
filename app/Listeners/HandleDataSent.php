<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleDataSent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DataSent $event)
    {

        $data = $event->data;


     ///   $secondController = new \App\Http\Controllers\SecondController();
        $secondController->receiveData($data);
    }
}
