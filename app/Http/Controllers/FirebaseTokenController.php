<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;

class FirebaseTokenController extends Controller
{
    public function getAccessToken(): JsonResponse
    {
        try {

            $accessToken = Cache::remember('firebase_access_token', now()->addHour(), function () {

                $credentialsFilePath = storage_path('app/json/warehouse-management-d8a87-9ef7b1d2a2ba.json');


                $client = new \Google_Client();
                $client->setAuthConfig($credentialsFilePath);
                $client->addScope('https://www.googleapis.com/auth/firebase.messaging');


                $client->fetchAccessTokenWithAssertion();
                $token = $client->getAccessToken();

                return $token['access_token'];
            });

            return response()->json(['access_token' => $accessToken]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to obtain access token',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
