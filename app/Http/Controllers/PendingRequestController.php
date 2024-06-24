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
class PendingRequestController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');

    }


    public function approveRequest($id)
    {


        $request = PendingRequest::findOrFail($id);
        $request->update(['status' => 'approved']);
        $request_data = $request ->requsetPending;

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
            'thereIsDisbility' => $request_data['thereIsDisbility'], //////////
            'needAttendant' => $request_data['needAttendant'],
            'NumberFamilyMember' => $request_data['NumberFamilyMember'],
            'thereIsDisbilityFamilyMember' => $request_data['thereIsDisbilityFamilyMember'], ///////////
            'losingBreadwinner' => $request_data['losingBreadwinner'],
            'governorate' => $request_data['governorate'],
            'address' => $request_data['address'],
            'email' => $request_data['email'],
            'numberline' => $request_data['numberline'],
            'numberPhone' => $request_data['numberPhone'],
            'numberId' => $request_data['numberId'],
            'educationalAttainment' => $level,     ////
            'previousTrainingCourses' => $request_data['previousTrainingCourses'],  ////
            'foreignLanguages' => $request_data['foreignLanguages'], ////
            'computerDriving' => $request_data['computerDriving'],
            'computerSkills' => $request_data['computerSkills'],
            'professionalSkills' => $request_data['professionalSkills'],  ///
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

        return response()->json(['message' => 'Request approved and student added.']);
    }








}
