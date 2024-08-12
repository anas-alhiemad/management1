<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Services\SendNotificationsService;
use App\Models\PendingRequest;
use App\Models\TrainerCourse;
use App\Models\BeneficiaryCourse;
use Validator;
class CourseController extends Controller
{
    public function addCourse(Request $request)
    {
        $validator =Validator::make($request->all(),[
            'nameCourse'=>'required|string',
            'coursePeriod'=>'required|integer',
            'sessionDoration'=>'required|numeric|min:0',
            'type' => 'required|string',
            'courseStatus' => 'required|string',
            'specialty' => 'required|string',
            'description' => 'required|string',
        ]);



        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $requestPending = PendingRequest::create(['requsetPending' => array_merge($validator->validated()),
        'type' =>'course',]);


        $admin = User::where('role', 'manager')->first();
        $service = new SendNotificationsService();
        $fcmToken = $admin->fcm_token;
        $messageData = [
            'title' => 'Test Notification',
            'body' => 'This is a test notification sent via FCM.'];
        $response = $service->sendByFcm($fcmToken, $messageData);
        return response()->json(['message' =>  'Request submitted successfully.']);

    }


    public function showAllCourses()
    {
        $courses = Course::all();
        return response()->json(['data' => $courses]);
    }

    public function showCourse($id)
    {
        $course = Course::findOrFail($id);
        return response()->json(['data' => $course]);
    }

    public function updateCourse(Request $request, $id)
    {
        $validator = validator::make($request->all(), [
            'nameCourse' => 'sometimes|required|string',
            'coursePeriod'=>'sometimes|integer',
            'sessionDoration'=>'sometimes|numeric|min:0',
            'type' => 'sometimes|required|string',
            'courseStatus' => 'sometimes|required|string',
            'specialty' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $course = Course::findOrFail($id);
        $course->update($validator->validated());

        return response()->json(['message' => 'Course updated successfully.', 'course' => $course]);
    }


    public function destroyCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json(['message' => 'Course deleted successfully.']);
    }



    public function updateStatus(Request $request, $id)
    {
            $validator = validator::make($request->all(), [
                'courseStatus' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $course = Course::find($id)->update([$request]);
            $beneficiaryWithCourses = BeneficiaryCourse::where('course_id',$id)->get();
            foreach ($beneficiaryWithCourses as $beneficiaryWithCours) {
                $beneficiaryWithCours->update(['status'=>'proceesing']);

            }


            return response()->json(['message' => 'Course status updated successfully.', 'course' => $course]);
    }



    public function searchCourse($request)
    {
        $query = $request;

        if (!$query) {
            return response()->json(['message' => 'Query parameter is required.'], 400);
        }

        $columns = [
            'nameCourse',
            'coursePeriod',
            'type',
            'courseStatus',
            'specialty',
            'description',
        ];


        $beneficiaries = Course::where(function($q) use ($columns, $query) {
            foreach ($columns as $column) {
                $q->orWhereRaw("LOWER($column) LIKE ?", ['%' . strtolower($query) . '%']);
            }
        })->get();


        if ($beneficiaries->isEmpty()) {
            return response()->json(['message' => 'No beneficiaries found with the provided query.'], 404);
        }

        return response()->json($beneficiaries);
    }

    public function ShowBeneficiaryWithCourse($id)
    {
        $beneficiaryWithCourse = BeneficiaryCourse::where('course_id',$id)->with('beneficiary')->get();
        return response()->json(['message'=>$beneficiaryWithCourse]);
    }

    public function ShowTrainerWithCourse($id)
    {
        $trainerWithCourse = TrainerCourse::where('course_id',$id)->with('trainer')->get();
        return response()->json(['message'=>$trainerWithCourse]);
    }


    public function RateCompletedCourses()
    {
        $sumAllPeriod = Course::sum('coursePeriod');
        $sumSessionsGiven = Course::sum('sessionsGiven');

        if ($sumAllPeriod == 0) {
            return response()->json(['message' => 'Total course period is zero, cannot calculate percentage.']);
        }

        $percentageForCompleted = number_format(($sumSessionsGiven / $sumAllPeriod) * 100,3);

        return response()->json(['RateCompletedCourses'=>$percentageForCompleted]);
    }





}
