<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainer;
use App\Models\User;
use App\Services\SendNotificationsService;
use App\Models\PendingRequest;
use App\Models\TrainerCourse;
use App\Models\Course;
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



    public function TrainerWithCourse(Request $request)
    {
        $validator =Validator::make($request->all(),[
            'countHours'=>'required|integer',
            'trainer_id'=>'required|integer',
            'course_id'=>'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $trainerWithCourse = TrainerCourse::where('trainer_id',$request->trainer_id)
                                                    ->where('course_id',$request->course_id)->first();

        if ($trainerWithCourse) {
            // if ($beneficiaryWithCourse->status  == 'pending')
           return response()->json(['message'=>'this trainer is already recorded this course'], 200);
        }

        $beneficiaryWithCourse = TrainerCourse::create(array_merge(
            $validator->validated()));
            return response()->json(['message => done  trainer is registered for this course']);

    }

    public function ShowTrainerWithCourse($id)
    {
        $trainerWithCourse = TrainerCourse::where('trainer_id',$id)->with('course')->get();
        return response()->json(['message'=>$trainerWithCourse]);
    }


    public function deleteTrainerWithCourse(Request $request)
    {
        $validator =Validator::make($request->all(),[
            'trainer_id'=>'required|integer',
            'course_id'=>'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $TrainerWithCourse = TrainerCourse::where('trainer_id',$request->trainer_id)
                                                    ->where('course_id',$request->course_id)->first();

        if ($TrainerWithCourse == null) {
           return response()->json(['message'=>'this trainer isn\'t already recorded this course'], 200);
        }
        else
        $TrainerWithCourse->delete();

            return response()->json(['message => done  trainer is delete for this course']);

    }

}
