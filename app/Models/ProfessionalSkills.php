<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Beneficiary;
class ProfessionalSkills extends Model
{
    use HasFactory;
    protected $table = 'professional_skills';
    protected $fillable = [
        'beneficiary_id',
        'jobTitle',
        'start',
        'end',
        'jobTasks',
    ];

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }

}


