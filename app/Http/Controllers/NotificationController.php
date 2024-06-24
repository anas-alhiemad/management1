<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class NotificationController extends Controller
{

    // need new method to send notification to admins

    public function sendFCMNotification($userId,$title,$body,$time = null)
    {
        $user = User::find($userId);
        $deviceToken = ' device token'; //$user->FCMToken;
        // just change the key
        $SERVER_API_KEY = 'AAAAI8r2hjc:APA91bED92CsLNY8hCA_QK6vfm7B75BjbNoeaQuvRTzOrPjtR3nkuARBnTMltD5y-27_TxpV4r9lfvWHwaQpFn74zyESGsiFCcwQuUkWTpaV3fhlk4BNG6RLfR53Bn2bB4pNHuHMmEHT';
        if(!empty($time)){
        $data = [
            "to" => $deviceToken,
            "priority"=>'high',
            "notification" => [
                "title" => $title,
                "body" => $body,
            ]
        ];
    
    }else{
            $data = [
                "to" => $deviceToken,
                "priority"=>'high',
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                   
                ]
            ];
        }
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
    try{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
       $res= curl_exec($ch);
       print($res);
    }catch(e){

    }


    }

}
