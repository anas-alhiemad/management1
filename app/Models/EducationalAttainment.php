<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Beneficiary;
class EducationalAttainment extends Model
{
    use HasFactory;
    protected $table = 'educational_attainments';
    protected $fillable = [
        'beneficiary_id',
        'specialization',
        'certificate',
        'graduationRate',
        'academicYear',
    ];

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }
}
