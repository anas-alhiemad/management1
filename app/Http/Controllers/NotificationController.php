<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{

    // need new method to send notification to admins

    public function sendFCMNotification($userId,$title,$body,$time = null)
    {try {

        $apiUrl = 'https://fcm.googleapis.com/v1/projects/warehouse-management-d8a87/messages:send';


        $apiUrl = str_replace('warehouse-management-d8a87', env('FIREBASE_PROJECT_ID'), $apiUrl);

        $user = User::find($userId);
        $deviceToken = $user->fcm_token;

        $accessToken = Cache::remember('access_token', now()->addHour(), function () {
            $credentialsFilePath = storage_path('app/json/warehouse-management-d8a87-9ef7b1d2a2ba.json');
            $client = new \Google_Client();
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->fetchAccessTokenWithAssertion();
            $token = $client->getAccessToken();
            return $token['access_token'];
        });

        $message = [
            "message" => [
                "token" => $deviceToken,
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                ],
            ],
        ];

        $response = Http::withHeader('Authorization', 'Bearer ' . $accessToken)->post($apiUrl, $message);

        return $response->json();
    } catch (\Throwable $th) {
        //throw $th;
    }

    }

}