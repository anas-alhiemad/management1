<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PendingRequest;
use App\Http\Controllers\PendingRequestController;
use App\Notifications\BeneficiaryAddedNotification;
use App\Services\SendNotificationsService;

use Validator;
use Auth;
class BeneficiaryController extends Controller
{

    public function addBeneficiary(Request $request)
    {


    	$validator = Validator::make($request->all(), [
            'serialNumber'=>'required|integer|unique:beneficiaries,serialnumber',
            'date'=>'required|date',
            'province' => 'required|string|between:2,100',
            'name' => 'required|string|between:2,50',
            'fatherName' => 'required|string|between:2,50',
            'motherName' => 'required|string|between:2,50',
            'gender' => 'required|string|between:2,20',
            'dateOfBirth' => 'required|string|between:2,100',
            'nots' => 'required|string|between:2,200',
            'maritalStatus' => 'required|string|between:2,100',
            'thereIsDisbility' => 'required|array',
            'needAttendant' => 'required|string|between:2,10',
            'NumberFamilyMember' => 'required|integer',
            'thereIsDisbilityFamilyMember' => 'required|array',
            'losingBreadwinner' => 'required|string|between:2,10',
            'governorate' => 'required|string|between:2,50',
            'address' => 'required|string|between:2,50',
            'email' => 'required|string|email|max:100',
            'numberline' => 'required|string|between:2,50',
            'numberPhone' => 'required|string|min:10',
            'numberId' => 'required|string|between:2,50',
            'educationalAttainment' => 'required|array' ,
            'previousTrainingCourses' =>'required|array',
            'foreignLanguages' => 'required|array',
            'computerDriving' => 'required|string|between:2,50',
            'computerSkills' => 'required|string|between:2,200',
            'professionalSkills' =>  'required|array',
            'sectorPreferences' =>  'required|string',
            'employment' => 'required|string|between:2,200',
            'supportRequiredTrainingLearning' => 'required|string|between:2,500',
            'supportRequiredEntrepreneurship' => 'required|string|between:2,500',
            'careerGuidanceCounselling' => 'required|string|between:2,500',
            'generalNotes' => 'required|string|between:2,500',

        ]);


        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
            }



        if($request->thereIsDisbility[0] != 'no'){
            $validator0 = Validator::make($request->all(),[
                'thereIsDisbility.*.nameDisbility' => 'required|string|between:2,50',
                'thereIsDisbility.*.rateDisbility' => 'required|string|between:2,50',
             ]);


            if($validator0->fails()){
                return response()->json($validator0->errors()->toJson(), 400);
                }
        }

        if($request->thereIsDisbilityFamilyMember[0] != 'no'){
            $validator00 = Validator::make($request->all(),[
                'thereIsDisbility.*.nameDisbility' => 'required|string|between:2,50',
//                'thereIsDisbility.*.rateDisbility' => 'required|string|between:2,50',
             ]);


            if($validator0->fails()){
                return response()->json($validator00->errors()->toJson(), 400);
                }
        }


        if($request->educationalAttainment[0] != 'no'  ){      // أامي

            $validator1 = Validator::make($request->all(),[
                'educationalAttainment.*.educationalAttainmentLevel' => 'required|string|between:2,50',
                'educationalAttainment.*.specialization' => 'nullable|string|between:2,200',
                'educationalAttainment.*.certificate' => 'nullable|string|between:2,200',
                'educationalAttainment.*.graduationRate' => 'nullable|string|between:2,200',
                'educationalAttainment.*.academicYear' => 'required|string|between:2,50',
             ]);


            if($validator1->fails()){
                return response()->json($validator1->errors()->toJson(), 400);
                }
        }


        if($request->foreignLanguages != null){
            $validator2 = Validator::make($request->all(),[
                'foreignLanguages.*.namelanguage' => 'required|string|between:2,200',
                'foreignLanguages.*.level' => 'required|string|between:2,20',
             ]);

            if($validator2->fails()){
                return response()->json($validator2->errors()->toJson(), 400);
                }

        }


        if($request->previousTrainingCourses != null){
            $validator3 = Validator::make($request->all(),[
                'previousTrainingCourses.*.certificateAndType' => 'required|string|between:2,200',
                'previousTrainingCourses.*.executingAgency' => 'required|string|between:2,200',
                'previousTrainingCourses.*.dateExecute' => 'required|string|between:2,200',
             ]);


            if($validator3->fails()){
                return response()->json($validator3->errors()->toJson(), 400);
                }

        }


        if($request->professionalSkills != null){
            $validator4 = Validator::make($request->all(),[
                'professionalSkills.*.jobTitle' => 'required|string|between:2,200',
                'professionalSkills.*.start' => 'required|string|between:2,200',
                'professionalSkills.*.end' => 'required|string|between:2,200',
                'professionalSkills.*.jobTasks' => 'required|string|between:2,200',
             ]);

            if($validator4->fails()){
                return response()->json($validator4->errors()->toJson(), 400);
                }
        }

        $requestPending = $request->all();

        $user =User::where('id',Auth::id())->firstOrFail();

        $userName = $user->name;

        // $requestPending =<<<"dataRequest"
        // Hello i'm $userName,
        // I will added the student is name : $request->name
        // and email  $request->email
        // dataRequest;

        $requestPending = PendingRequest::create(['requsetPending' => array_merge($validator->validated())]);

        $admin = User::where('role', 'manager')->first();
        $admin->notify(new BeneficiaryAddedNotification($requestPending,$userName));

        $service = new SendNotificationsService();

        $fcmToken = $admin->fcm_token;
        $messageData = [
            'title' => 'Test Notification',
            'body' => 'This is a test notification sent via FCM.',
        ];

        $response = $service->sendByFcm($fcmToken, $messageData);

        return response()->json(['message' => 'Request submitted successfully.','data'=>$validator->validated()]);

// this commit 
    }
}



