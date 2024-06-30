<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Services\SendNotificationsService;
use App\Models\PendingRequest;
use Validator;
class CourseController extends Controller
{
    public function addCourse(Request $request)
    {
        $validator =Validator::make($request->all(),[
            'nameCourse'=>'required|string',
            'coursePeriod'=>'required|string',
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
            'coursePeriod' => 'sometimes|required|string',
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

            $course = Course::findOrFail($id);
            $course->courseStatus = $request->courseStatus;
            $course->save();

            return response()->json(['message' => 'Course status updated successfully.', 'course' => $course]);
    }


}
