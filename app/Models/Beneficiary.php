<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Disbility;
use App\Models\EducationalAttainment;
use App\Models\previousTrainingCourses;
use App\Models\foreignLanguages;
use App\Models\ProfessionalSkills;
use App\Models\Document;
use App\Models\BeneficiaryCourse;

class Beneficiary extends Model
{
    use HasFactory;

        protected $table = 'beneficiaries';


        protected $fillable = [
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
//            'thereIsDisbility',
            'needAttendant',
            'NumberFamilyMember',
 //           'thereIsDisbilityFamilyMember',
            'losingBreadwinner',
            'governorate',
            'address',
            'email',
            'numberline',
            'numberPhone',
            'numberId',
            'educationalAttainment',
  //          'previousTrainingCourses',
 //            'foreignLanguages',
            'computerDriving',
            'computerSkills',
//           'professionalSkills',
            'sectorPreferences',
            'employment',
            'supportRequiredTrainingLearning',
            'supportRequiredEntrepreneurship',
            'careerGuidanceCounselling',
            'generalNotes'
        ];


        protected $casts = [
            'thereIsDisbility' => 'array',
            'thereIsDisbilityFamilyMember' => 'array',
            'educationalAttainment' => 'array',
            'previousTrainingCourses' => 'array',
            'foreignLanguages' => 'array',
            'professionalSkills' => 'array',
            'sectorPreferences' => 'array'
        ];

        public function disbility() {
            return $this->hasMany(Disbility::class,'beneficiary_id');
        }
        public function educationalAttainment() {
            return $this->hasMany(EducationalAttainment::class,'beneficiary_id');
        }
        public function previoustrainingcourses() {
            return $this->hasMany(previousTrainingCourses::class,'beneficiary_id');
        }
        public function foreignlanguages() {
            return $this->hasMany(foreignLanguages::class,'beneficiary_id');
        }
        public function ProfessionalSkills() {
            return $this->hasMany(ProfessionalSkills::class,'beneficiary_id');
        }

        public function document() {
            return $this->hasMany(Document::class,'beneficiary_id');
        }
        public function beneficiaryCourses() {
            return $this->hasMany(BeneficiaryCourse::class,'beneficiary_id');
        }

}
