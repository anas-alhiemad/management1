<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BeneficiaryCourse;
class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $fillable = [
        'nameCourse',
        'coursePeriod',
        'type',
        'courseStatus',
        'specialty',
        'description',
    ];

    public function beneficiaryCourses() {
        return $this->hasMany(BeneficiaryCourse::class,'course_id');
    }

}
