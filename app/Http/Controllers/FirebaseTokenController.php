<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Validator;
use App\Notifications\addedNotification;
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

    public function storeNotification(Request $request){
        $validator = Validator::make($request->all(), [
            'Fcm_token' => 'required|string',
            'messageNotification' => 'required|array',
            'messageNotification.titleNotification' => 'required|string',
            'messageNotification.bodyNotification' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $user = User::where('fcm_token', $request->Fcm_token)->first();

        $messageData = $request->messageNotification ;
        $user->notify(new addedNotification($messageData));   
        return response()->json([
            'messageNotification' =>"done store data"
        ]);
    }
    public function showNotification($id){

        $user = User::find($id); 
        $notifications = $user->notifications;
        return response()->json(["notifications" => $notifications]);
    }



    }

