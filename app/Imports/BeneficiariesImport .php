<?php
namespace App\Imports;

use App\Models\Beneficiary;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;

class BeneficiariesImport implements ToModel, WithHeadingRow, WithMapping
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function map($row): array
    {
        return [
            'serialNumber' => $row['serialNumber'],
            'date' => $row['date'],
            'province' => $row['province'],
            'name' => $row['name'],
            'fatherName' => $row['fatherName'],
            'motherName' => $row['motherName'],
            'gender' => $row['gender'],
            'dateOfBirth' => $row['dateOfBirth'],
            'nots' => $row['nots'],
            'maritalStatus' => $row['maritalStatus'],
            'needAttendant' => $row['needAttendant'],
            'NumberFamilyMember' => $row['NumberFamilyMember'],
            'losingBreadwinner' => $row['losingBreadwinner'],
            'governorate' => $row['governorate'],
            'address' => $row['address'],
            'email' => $row['email'],
            'numberline' => $row['numberline'],
            'numberPhone' => $row['numberPhone'],
            'numberId' => $row['numberId'],
            'educationalAttainment' => json_encode($row['educationalAttainment']),
            'computerDriving' => $row['computerDriving'],
            'computerSkills' => json_encode($row['computerSkills']),
            'sectorPreferences' => json_encode($row['sectorPreferences']),
            'employment' => $row['employment'],
            'supportRequiredTrainingLearning' => $row['supportRequiredTrainingLearning'],
            'supportRequiredEntrepreneurship' => $row['supportRequiredEntrepreneurship'],
            'careerGuidanceCounselling' => $row['careerGuidanceCounselling'],
            'generalNotes' => $row['generalNotes'],
        ];
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Create Beneficiary model instance
        $beneficiary = new Beneficiary([
            'serialNumber' => $row['serialNumber'],
            'date' => $row['date'],
            'province' => $row['province'],
            'name' => $row['name'],
            'fatherName' => $row['fatherName'],
            'motherName' => $row['motherName'],
            'gender' => $row['gender'],
            'dateOfBirth' => $row['dateOfBirth'],
            'nots' => $row['nots'],
            'maritalStatus' => $row['maritalStatus'],
            'needAttendant' => $row['needAttendant'],
            'NumberFamilyMember' => $row['NumberFamilyMember'],
            'losingBreadwinner' => $row['losingBreadwinner'],
            'governorate' => $row['governorate'],
            'address' => $row['address'],
            'email' => $row['email'],
            'numberline' => $row['numberline'],
            'numberPhone' => $row['numberPhone'],
            'numberId' => $row['numberId'],
            'educationalAttainment' => json_encode($row['educationalAttainment']),
            'computerDriving' => $row['computerDriving'],
            'computerSkills' => json_encode($row['computerSkills']),
            'sectorPreferences' => json_encode($row['sectorPreferences']),
            'employment' => $row['employment'],
            'supportRequiredTrainingLearning' => $row['supportRequiredTrainingLearning'],
            'supportRequiredEntrepreneurship' => $row['supportRequiredEntrepreneurship'],
            'careerGuidanceCounselling' => $row['careerGuidanceCounselling'],
            'generalNotes' => $row['generalNotes'],
        ]);

        // Save Beneficiary
        $beneficiary->save();

        // Example of creating related records (adjust as per your relationships)
        $beneficiary->disbility()->createMany($row['disbility']);
        $beneficiary->educationalAttainment()->createMany($row['educationalAttainment']);
        $beneficiary->previoustrainingcourses()->createMany($row['previoustrainingcourses']);
        $beneficiary->foreignlanguages()->createMany($row['foreignlanguages']);
        $beneficiary->ProfessionalSkills()->createMany($row['ProfessionalSkills']);
        // Add other relationships as needed...

        return $beneficiary;
    }
}
