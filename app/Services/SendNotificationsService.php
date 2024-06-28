<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SendNotificationsService
{
    public function sendByFcm(string $fcmToken, array $messageData)
    {
        $apiUrl = 'https://fcm.googleapis.com/v1/projects/warehouse-management-d8a87/messages:send';


        $apiUrl = str_replace('warehouse-management-d8a87', env('FIREBASE_PROJECT_ID'), $apiUrl);

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
                "token" => $fcmToken,
                "notification" => [
                    "title" => $messageData['title'],
                    "body" => $messageData['body'],
                ],
            ],
        ];

        $response = Http::withHeader('Authorization', 'Bearer ' . $accessToken)->post($apiUrl, $message);

        return $response->json();
    }
}
