<?php
namespace App\Imports;

use App\Models\Beneficiary;
use App\Models\Disbility;
use App\Models\EducationalAttainmentLevel;
use App\Models\PreviousTrainingCourse;
use App\Models\ForeignLanguage;
use App\Models\ProfessionalSkill;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BeneficiariesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Create or update the Beneficiary record
        $beneficiary = Beneficiary::Create(
            ['serialNumber' => $row['serialnumber'], // assuming 'serialNumber' is unique and used to identify the beneficiary
                'date' => $row['date'],
                'province' => $row['province'],
                'name' => $row['name'],
                'fatherName' => $row['fathername'],
                'motherName' => $row['mothername'],
                'gender' => $row['gender'],
                'dateOfBirth' => $row['dateofbirth'],
                'nots' => $row['nots'],
                'maritalStatus' => $row['maritalstatus'],
                'needAttendant' => $row['needattendant'],
                'NumberFamilyMember' => $row['numberfamilymember'],
                'losingBreadwinner' => $row['losingbreadwinner'],
                'governorate' => $row['governorate'],
                'address' => $row['address'],
                'email' => $row['email'],
                'numberline' => $row['numberline'],
                'numberPhone' => $row['numberphone'],
                'numberId' => $row['numberid'],
                'educationalAttainment' => $row['educationalattainment'],
                'computerDriving' => $row['computerdriving'],
                'computerSkills' => $row['computerskills'],
                'sectorPreferences' => $row['sectorpreferences'],
                'employment' => $row['employment'],
                'supportRequiredTrainingLearning' => $row['supportrequiredtraininglearning'],
                'supportRequiredEntrepreneurship' => $row['supportrequiredentrepreneurship'],
                'careerGuidanceCounselling' => $row['careerguidancecounselling'],
                'generalNotes' => $row['generalnotes'],
            ]
        );

        // Handle relationships (e.g., disabilities, educational attainments, etc.)

        // Disabilities
        $disabilities = explode(', ', $row['disabilities']);
        foreach ($disabilities as $disability) {
            list($name, $rate) = explode(' - ', $disability);
            $beneficiary->disbility()->Create(
                ['nameDisbility' => $name,
                'rateDisbility' => $rate]
            );
        }

        // Educational Attainments
        $educationalAttainments = explode(', ', $row['educational_attainments']);
        foreach ($educationalAttainments as $attainment) {
            list($specialization, $certificate, $graduationRate, $academicYear) = explode(' - ', $attainment);
            $beneficiary->educationalAttainmentLevel()->Create(
              [  'specialization' => $specialization,
                    'certificate' => $certificate,
                    'graduationRate' => $graduationRate,
                    'academicYear' => $academicYear
                ]
            );
        }

        // Previous Training Courses
        $trainingCourses = explode(', ', $row['previous_training_courses']);
        foreach ($trainingCourses as $course) {
            list($certificateAndType, $executingAgency, $dateExecute) = explode(' - ', $course);
            $beneficiary->previoustrainingcourses()->Create(
                ['certificateAndType' => $certificateAndType,
                    'executingAgency' => $executingAgency,
                    'dateExecute' => $dateExecute
                ]
            );
        }

        // Foreign Languages
        $languages = explode(', ', $row['foreign_languages']);
        foreach ($languages as $language) {
            list($nameLanguage, $level) = explode(' - ', $language);
            $beneficiary->foreignlanguages()->Create(
                ['namelanguage' => $nameLanguage,
                'level' => $level]
            );
        }

        // Professional Skills
        $skills = explode('_ ', $row['professional_skills']);
        foreach ($skills as $skill) {
            list($jobTitle, $start, $end, $jobTasks) = explode(' - ', $skill);
            $beneficiary->ProfessionalSkills()->Create(
                ['jobTitle' => $jobTitle,
                    'start' => $start,
                    'end' => $end,
                    'jobTasks' => $jobTasks
                ]
            );
        }

        return $beneficiary;
    }
}
