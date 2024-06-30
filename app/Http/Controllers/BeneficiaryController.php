<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Disbility;
use App\Models\ProfessionalSkills;
use App\Models\foreignLanguages;
use App\Models\previousTrainingCourses;
use App\Models\EducationalAttainment;
use App\Models\PendingRequest;
use App\Models\Beneficiary;
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
        $requestPending = PendingRequest::create(['requsetPending' => array_merge($validator->validated()),
                                                  'type' =>'beneficiary',
                                                            ]);


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

    }


    public function getAllBeneficiary()
    {
        $beneficiary=Beneficiary::with('disbility','educationalAttainment','previoustrainingcourses','foreignlanguages','ProfessionalSkills') ->get();
        return response()->json(['dataBeneficiary' => $beneficiary]);
    }
    public function getBeneficiary($id)
    {
        $beneficiary=Beneficiary::with('disbility','educationalAttainment','previoustrainingcourses','foreignlanguages','ProfessionalSkills')
          -> where('id',$id)->get();
        return response()->json(['dataBeneficiary' => $beneficiary]);
    }



    public function updateBeneficiary(Request $request, $id)
{


    $validator = Validator::make($request->all(), [
        'serialNumber'=>'required|integer|unique:beneficiaries,serialnumber,' . $id,
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
        'educationalAttainment' => 'required|array',
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

    $beneficiary = Beneficiary::find($id);

    $request_data = $request->all();

    $educationalAttainmentArraylevel = $request_data['educationalAttainment'];
    foreach ($educationalAttainmentArraylevel as $attainment) {
        $level = $attainment['educationalAttainmentLevel'];
        break;}


    $beneficiary->update([
        'serialNumber' => $request_data['serialNumber'],
        'date' => $request_data['date'],
        'province' => $request_data['province'],
        'name' => $request_data['name'],
        'fatherName' => $request_data['fatherName'],
        'motherName' => $request_data['motherName'],
        'gender' => $request_data['gender'],
        'dateOfBirth' => $request_data['dateOfBirth'],
        'nots' => $request_data['nots'],
        'maritalStatus' => $request_data['maritalStatus'],
        'needAttendant' => $request_data['needAttendant'],
        'NumberFamilyMember' => $request_data['NumberFamilyMember'],
        'losingBreadwinner' => $request_data['losingBreadwinner'],
        'governorate' => $request_data['governorate'],
        'address' => $request_data['address'],
        'email' => $request_data['email'],
        'numberline' => $request_data['numberline'],
        'numberPhone' => $request_data['numberPhone'],
        'numberId' => $request_data['numberId'],
        'educationalAttainment' => $level,
        'computerDriving' => $request_data['computerDriving'],
        'computerSkills' => $request_data['computerSkills'],
        'sectorPreferences' => $request_data['sectorPreferences'],
        'employment' => $request_data['employment'],
        'supportRequiredTrainingLearning' => $request_data['supportRequiredTrainingLearning'],
        'supportRequiredEntrepreneurship' => $request_data['supportRequiredEntrepreneurship'],
        'careerGuidanceCounselling' => $request_data['careerGuidanceCounselling'],
        'generalNotes' => $request_data['generalNotes'],
    ]);

    $this->updateDisabilities($request_data, $beneficiary);

    $this->updateEducationalAttainments($request_data, $beneficiary);

    $this->updatePreviousTrainingCourses($request_data, $beneficiary);

    $this->updateForeignLanguages($request_data, $beneficiary);

    $this->updateProfessionalSkills($request_data, $beneficiary);

    return response()->json(['message' => 'Beneficiary updated successfully.']);
}


private function updateDisabilities($request_data, $beneficiary)
{

    Disbility::where('beneficiary_id', $beneficiary->id)->delete();


    $thereIsDisbilityArray = $request_data['thereIsDisbility'];
    if($thereIsDisbilityArray != null){
        foreach ($thereIsDisbilityArray as $disbility) {
            Disbility::create([
                'beneficiary_id' => $beneficiary->id,
                'nameDisbility' => $disbility['nameDisbility'],
                'rateDisbility' => $disbility['rateDisbility'],
            ]);
        }
    }

    $thereIsDisbilityFamilyMemberArray = $request_data['thereIsDisbilityFamilyMember'];
    if($thereIsDisbilityFamilyMemberArray != null){
        foreach ($thereIsDisbilityFamilyMemberArray as $disbility) {
            Disbility::create([
                'beneficiary_id' => $beneficiary->id,
                'nameDisbility' => $disbility['nameDisbility'],
            ]);
        }
    }
}


private function updateEducationalAttainments($request_data, $beneficiary)
{

    EducationalAttainment::where('beneficiary_id', $beneficiary->id)->delete();

    $educationalAttainmentArray = $request_data['educationalAttainment'];
    if($educationalAttainmentArray != null){
        foreach ($educationalAttainmentArray as $educationalAttainment) {
            EducationalAttainment::create([
                'beneficiary_id' => $beneficiary->id,
                'specialization' => $educationalAttainment['specialization'],
                'certificate' => $educationalAttainment['certificate'],
                'graduationRate' => $educationalAttainment['graduationRate'],
                'academicYear' => $educationalAttainment['academicYear'],
            ]);
        }
    }
}



private function updatePreviousTrainingCourses($request_data, $beneficiary)
{

    previousTrainingCourses::where('beneficiary_id', $beneficiary->id)->delete();

    $previousTrainingCoursesArray = $request_data['previousTrainingCourses'];
    if($previousTrainingCoursesArray != null){
        foreach ($previousTrainingCoursesArray as $previousTrainingCourses) {
            previousTrainingCourses::create([
                'beneficiary_id' => $beneficiary->id,
                'certificateAndType' => $previousTrainingCourses['certificateAndType'],
                'executingAgency' => $previousTrainingCourses['executingAgency'],
                'dateExecute' => $previousTrainingCourses['dateExecute'],
            ]);
        }
    }
}

private function updateForeignLanguages($request_data, $beneficiary)
{

    foreignLanguages::where('beneficiary_id', $beneficiary->id)->delete();

    $foreignLanguagesArray = $request_data['foreignLanguages'];
    if($foreignLanguagesArray != null){
        foreach ($foreignLanguagesArray as $foreignLanguage) {
            foreignLanguages::create([
                'beneficiary_id' => $beneficiary->id,
                'namelanguage' => $foreignLanguage['namelanguage'],
                'level' => $foreignLanguage['level'],
            ]);
        }
    }



}

private function updateProfessionalSkills($request_data, $beneficiary)
{

    ProfessionalSkills::where('beneficiary_id', $beneficiary->id)->delete();

    $professionalSkillsArray = $request_data['professionalSkills'];
    if($professionalSkillsArray != null){
        foreach ($professionalSkillsArray as $professionalSkill) {
            ProfessionalSkills::create([
                'beneficiary_id' => $beneficiary->id,
                'jobTitle' => $professionalSkill['jobTitle'],
                'start' => $professionalSkill['start'],
                'end' => $professionalSkill['end'],
                'jobTasks' => $professionalSkill['jobTasks'],
            ]);
        }
    }
}


public function deleteBeneficiary($id)
{

   Beneficiary::where('id', $id)->delete();
   return response()->json(['message' => 'Beneficiary deleted successfully.']);
}


public function searchBeneficiary($request)
{
    $query = $request;

    if (!$query) {
        return response()->json(['message' => 'Query parameter is required.'], 400);
    }

    $columns = [
        'serialNumber',
        'date',
        'province',
        'name',
        'fatherName',
        'motherName',
        'gender',
        'dateOfBirth',
        'nots',
        'maritalStatus',
        'needAttendant',
        'NumberFamilyMember',
        'losingBreadwinner',
        'governorate',
        'address',
        'email',
        'numberline',
        'numberPhone',
        'numberId',
        'educationalAttainment',
        'computerDriving',
        'computerSkills',
        'sectorPreferences',
        'employment',
        'supportRequiredTrainingLearning',
        'supportRequiredEntrepreneurship',
        'careerGuidanceCounselling',
        'generalNotes'
    ];


    $beneficiaries = Beneficiary::where(function($q) use ($columns, $query) {
        foreach ($columns as $column) {
            $q->orWhereRaw("LOWER($column) LIKE ?", ['%' . strtolower($query) . '%']);
        }
    })->get();


    if ($beneficiaries->isEmpty()) {
        return response()->json(['message' => 'No beneficiaries found with the provided query.'], 404);
    }

    return response()->json($beneficiaries);
}





}



