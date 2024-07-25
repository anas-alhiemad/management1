<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\User;
use App\Services\SendNotificationsService;
use App\Models\PendingRequest;
use Validator;

class TrainerController extends Controller
{
    public function showAllTrainer()
    {
        $trainers = Trainer::all();
        return response()->json(['message' => $trainers]);
    }


    public function addTrainer(Request $request)
    {
        $validator =Validator::make($request->all(),[
            'name'=>'required|string',
            'email' => 'required|string|email|max:100||unique:trainers,email',
            'phone' => 'required|integer|min:10',
            'address' => 'required|string',
            'specialty' => 'required|string',
            'description' => 'required|string',
        ]);



        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $requestPending = PendingRequest::create(['requsetPending' => array_merge($validator->validated()),
        'type' =>'trainer',]);


        $admin = User::where('role', 'manager')->first();
        $service = new SendNotificationsService();
        $fcmToken = $admin->fcm_token;
        $messageData = [
            'title' => 'Test Notification',
            'body' => 'This is a test notification sent via FCM.'];
        $response = $service->sendByFcm($fcmToken, $messageData);
        return response()->json(['message' =>  'Request submitted successfully.']);

    }



    public function showTrainer($id)
    {
        $trainer = Trainer::findOrFail($id);
        return response()->json(['data' => $trainer]);
    }


    public function searchTrainer($request)
    {
        $query = $request;

        if (!$query) {
            return response()->json(['message' => 'Query parameter is required.'], 400);
        }

        $columns = [
            'name',
            'email',
            'phone',
            'address',
            'specialty',
            'description',
        ];


        $trainer = Trainer::where(function($q) use ($columns, $query) {
            foreach ($columns as $column) {
                $q->orWhereRaw("LOWER($column) LIKE ?", ['%' . strtolower($query) . '%']);
            }
        })->get();


        if ($trainer->isEmpty()) {
            return response()->json(['message' => 'No Trainer found with the provided query.'], 404);
        }

        return response()->json($trainer);
    }


    public function destroyTrainer($id)
    {
        $trainer = Trainer::findOrFail($id);
        $trainer->delete();

        return response()->json(['message' => 'Trainer deleted successfully.']);
    }



}
