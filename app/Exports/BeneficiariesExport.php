<?php
namespace App\Exports;

use App\Models\Beneficiary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BeneficiariesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $fields;
    protected $filters;
    public function __construct(array $fields, $filters)
    {
        $this->fields = $fields;
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // $columns = ['date',
        // 'province',            'gender',
        //     'dateOfBirth',]:
        $beneficiary = Beneficiary::with('disbility', 'educationalAttainmentLevel', 'previoustrainingcourses', 'foreignlanguages', 'ProfessionalSkills');
        foreach ($this->filters as $key => $value) {
            if (!empty($value)) {
                $beneficiary = Beneficiary::with('disbility', 'educationalAttainmentLevel', 'previoustrainingcourses', 'foreignlanguages', 'ProfessionalSkills')->where($key,$value);
            }
        }

        return $beneficiary->get();
     //   return Beneficiary::select($this->fields)->with('disbility', 'educationalAttainmentLevel', 'previoustrainingcourses', 'foreignlanguages', 'ProfessionalSkills')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $relationHeadings = ['Disabilities', 'Educational Attainments', 'Previous Training Courses', 'Foreign Languages', 'Professional Skills'];
        return array_merge($this->fields, $relationHeadings);
    }

    /**
     * @param $beneficiary
     * @return array
     */
    public function map($beneficiary): array
    {
        $row = [];
        foreach ($this->fields as $field) {
            $row[] = $beneficiary->$field;
        }

        // Add related data
   //     $row[] = $beneficiary->educationalAttainment->pluck('educational_field')->implode(', ');
        $row[] = $beneficiary->disbility->map(function($disbility) {
            return $disbility->nameDisbility . ' - ' . $disbility->rateDisbility;
        })->implode(', ');

        $row[] = $beneficiary->educationalAttainmentLevel->map(function($educationalAttainmentLevel) {
            return $educationalAttainmentLevel->specialization . ' - ' . $educationalAttainmentLevel->certificate. ' - ' . $educationalAttainmentLevel->graduationRate. ' - ' . $educationalAttainmentLevel->academicYear;
        })->implode(', ');

           $row[] = $beneficiary->previoustrainingcourses->map(function($previoustrainingcourses) {
            return $previoustrainingcourses->certificateAndType . ' - ' . $previoustrainingcourses->executingAgency. ' - ' . $previoustrainingcourses->dateExecute;
        })->implode(', ');

           $row[] = $beneficiary->foreignlanguages->map(function($foreignlanguages) {
            return $foreignlanguages->namelanguage . ' - ' . $foreignlanguages->level;
        })->implode(', ');

        $row[] = $beneficiary->ProfessionalSkills->map(function($ProfessionalSkills) {
            return $ProfessionalSkills->jobTitle . ' - ' . $ProfessionalSkills->start. ' - ' . $ProfessionalSkills->end. ' - ' . $ProfessionalSkills->jobTasks;
        })->implode(' _ ');

        return $row;
    }
}
