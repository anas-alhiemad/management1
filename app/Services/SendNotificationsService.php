<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendNotificationsService
{
    protected $apiUrl;
    protected $credentialsFilePath;

    public function __construct()
    {

        $this->apiUrl = 'https://fcm.googleapis.com/v1/projects/' . env('FIREBASE_PROJECT_ID') . '/messages:send';
        $this->credentialsFilePath = storage_path('app/json/warehouse-management-d8a87-9ef7b1d2a2ba.json');
    }

    public function sendByFcm(string $fcmToken, array $messageData)
    {
        try {
            $accessToken = $this->getAccessToken();
            $message = $this->buildMessage($fcmToken, $messageData);

            $response = Http::withHeader('Authorization', 'Bearer ' . $accessToken)
                            ->post($this->apiUrl, $message);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('FCM send notification failed', [
                'response' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send notification',
                'error' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('Error in sendByFcm method', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getAccessToken()
    {
        return Cache::remember('access_token', now()->addHour(), function () {
            $client = new \Google_Client();
            $client->setAuthConfig($this->credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->fetchAccessTokenWithAssertion();
            $token = $client->getAccessToken();
            return $token['access_token'];
        });
    }

    protected function buildMessage(string $fcmToken, array $messageData)
    {
        return [
            "message" => [
                "token" => $fcmToken,
                "notification" => [
                    "title" => $messageData['title'],
                    "body" => $messageData['body'],
                ],
            ],
        ];
    }
}









// namespace App\Services;

// use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\Cache;

// class SendNotificationsService
// {
//     public function sendByFcm(string $fcmToken, array $messageData)
//     {
//         $apiUrl = 'https://fcm.googleapis.com/v1/projects/warehouse-management-d8a87/messages:send';


//         $apiUrl = str_replace('warehouse-management-d8a87', env('FIREBASE_PROJECT_ID'), $apiUrl);

//         $accessToken = Cache::remember('access_token', now()->addHour(), function () {
//             $credentialsFilePath = storage_path('app/json/warehouse-management-d8a87-9ef7b1d2a2ba.json');
//             $client = new \Google_Client();
//             $client->setAuthConfig($credentialsFilePath);
//             $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
//             $client->fetchAccessTokenWithAssertion();
//             $token = $client->getAccessToken();
//             return $token['access_token'];
//         });

//         $message = [
//             "message" => [
//                 "token" => $fcmToken,
//                 "notification" => [
//                     "title" => $messageData['title'],
//                     "body" => $messageData['body'],
//                 ],
//             ],
//         ];

//         $response = Http::withHeader('Authorization', 'Bearer ' . $accessToken)->post($apiUrl, $message);

//         return $response->json();
//     }
// }
