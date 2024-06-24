<?php

namespace App\Models;
use App\Models\Beneficiary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class previousTrainingCourses extends Model
{
    use HasFactory;


    protected $table = 'previous_training_courses';
    protected $fillable = [
        'beneficiary_id',
        'certificateAndType',
        'executingAgency',
        'dateExecute',
    ];

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }
}
