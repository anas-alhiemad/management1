<?php
namespace App\Exports;

use App\Models\Beneficiary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BeneficiariesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Beneficiary::with(['disbility', 'educationalAttainment', 'previoustrainingcourses', 'foreignlanguages', 'ProfessionalSkills'])->get();
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
        $row[] = $beneficiary->disbility->pluck('disbility_field')->implode(', ');
        $row[] = $beneficiary->educationalAttainment->pluck('educational_field')->implode(', ');
        $row[] = $beneficiary->previoustrainingcourses->pluck('training_course_field')->implode(', ');
        $row[] = $beneficiary->foreignlanguages->pluck('language_field')->implode(', ');
        $row[] = $beneficiary->ProfessionalSkills->pluck('skill_field')->implode(', ');
        return $row;
    }
}
