<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\PendingRequest;
use App\Models\Disbility;
use App\Models\EducationalAttainment;
use App\Models\previousTrainingCourses;
use App\Models\foreignLanguages;
use App\Models\ProfessionalSkills;
use App\Models\Course;
use App\Models\Item;

use Validator;
use Auth;

class PendingRequestController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');

    }

    public function showAllRequestBeneficiary()
    {
        $request = PendingRequest::where('status','pending')
                                    ->where('type', 'beneficiary')->get();

        return response()->json(['message' => 'all the  pendingRequest', 'dataRequest' => $request], 200);
    }
    public function showAllRequestCourses()
    {
        $request = PendingRequest::where('status','pending')
                                    ->where('type', 'course')->get();

        return response()->json(['message' => 'all the  pendingRequest', 'dataRequest' => $request], 200);
    }

    public function showAllRequestItems()
    {
        $request = PendingRequest::where('status','pending')
                                    ->where('type', 'item')->get();

        return response()->json(['message' => 'all the  pendingRequest', 'dataRequest' => $request], 200);
    }




    public function approveRequest($id)
    {
        $request = PendingRequest::findOrFail($id);
        $type = $request ->type;
        $request_data = $request ->requsetPending;

        if($type == 'beneficiary'){
        $educationalAttainmentArraylevel = $request_data['educationalAttainment'];
        foreach ($educationalAttainmentArraylevel as $attainment) {
            $level = $attainment['educationalAttainmentLevel'];
            break;}

        $beneficiary =  Beneficiary::create([
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
       //     'thereIsDisbility' => $request_data['thereIsDisbility'], //////////
            'needAttendant' => $request_data['needAttendant'],
            'NumberFamilyMember' => $request_data['NumberFamilyMember'],
        //    'thereIsDisbilityFamilyMember' => $request_data['thereIsDisbilityFamilyMember'], ///////////
            'losingBreadwinner' => $request_data['losingBreadwinner'],
            'governorate' => $request_data['governorate'],
            'address' => $request_data['address'],
            'email' => $request_data['email'],
            'numberline' => $request_data['numberline'],
            'numberPhone' => $request_data['numberPhone'],
            'numberId' => $request_data['numberId'],
            'educationalAttainment' => $level,     ////
       //     'previousTrainingCourses' => $request_data['previousTrainingCourses'],  ////
      //      'foreignLanguages' => $request_data['foreignLanguages'], ////
            'computerDriving' => $request_data['computerDriving'],
            'computerSkills' => $request_data['computerSkills'],
        //    'professionalSkills' => $request_data['professionalSkills'],  ///
            'sectorPreferences' => $request_data['sectorPreferences'],
            'employment' => $request_data['employment'],
            'supportRequiredTrainingLearning' => $request_data['supportRequiredTrainingLearning'],
            'supportRequiredEntrepreneurship' => $request_data['supportRequiredEntrepreneurship'],
            'careerGuidanceCounselling' => $request_data['careerGuidanceCounselling'],
            'generalNotes' => $request_data['generalNotes'],
        ]);

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
        $professionalSkillsArray = $request_data['professionalSkills'];
        if($foreignLanguagesArray != null){
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
        $request->update(['status' => 'approved']);
        return response()->json(['message' => 'Request approved and student added.']);
    }


    elseif($type == 'course')
    {
        $course = Course::create([
        'nameCourse' => $request_data['nameCourse'],
        'coursePeriod' => $request_data['coursePeriod'],
        'type' => $request_data['type'],
        'courseStatus' => $request_data['courseStatus'],
        'specialty' => $request_data['specialty'],
        'description' => $request_data['description'],]);

        $request->update(['status' => 'approved']);
        return response()->json(['message' => 'Request approved and course added.']);

    } elseif($type == 'item')
    {
        $pendingRequest = json_decode($request_data, true);
        if (isset($pendingRequest['id']) && $pendingRequest['id']) {
        $item = Item::find($pendingRequest['id']);
        if ($item) {
            $item->update($pendingRequest);
        } else {
            Item::create($pendingRequest);
        }
        $request->update(['status' => 'approved']);
        return response()->json(['message' => 'Request approved and item added.']);

    }else{
        Item::create($pendingRequest);

    $request->update(['status' => 'approved']);
    return response()->json(['message' => 'Request approved and item added.']);
    }
    }

    }



    public function rejectRequest($id)
    {
        $request = PendingRequest::findOrFail($id);
        $request->update(['status' => 'rejected']);
        return response()->json(['message' => 'Request rejected.']);
    }


    public function updateRequest(Request $request, $id)
    {

        $pendingRequest = PendingRequest::where('status','pending')->findOrFail($id);
        $type = $request ->type;
        if($type = 'beneficiary')
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

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

            $pendingRequest->update(['requsetPending' => $validator->validated()]);

            return response()->json( $pendingRequest);
        }

        elseif($type = 'course')
        {
            $validator = validator::make($request->all(),[
                'nameCourse'=>'required|string',
                'coursePeriod'=>'required|string',
                'type' => 'required|string',
                'courseStatus' => 'required|string',
                'specialty' => 'required|string',
                'description' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

                $pendingRequest->update(['requsetPending' => $validator->validated()]);

                return response()->json(['message' => 'Pending request updated successfully.', 'data' => $pendingRequest]);
        }





    }


    public function deleteRequest($id)

    {

        $pendingRequest = PendingRequest::where('id', $id);

        $pendingRequest->delete();

        return response()->json(['message' => 'Pending request deleted successfully.']);

     }




}
