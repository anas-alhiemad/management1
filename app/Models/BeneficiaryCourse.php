<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Beneficiary;
use App\Models\Course;
class BeneficiaryCourse extends Model
{
    use HasFactory;
    protected $table = 'beneficiary_courses';
    protected $fillable = [
      'beneficiary_id',
      'course_id',
      'status'
    ];

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

}
