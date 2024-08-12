<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use App\Models\User;
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

    public function getFcmToken($role)
    {
        $Fcm_token = User::where('role',$role)->select('fcm_token')->first();
        return response()->json([
            'Fcm_token' =>$Fcm_token
        ]);
    }
    }

