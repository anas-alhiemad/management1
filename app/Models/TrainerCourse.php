<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Trainer;
class TrainerCourse extends Model
{
    use HasFactory;
    protected $table = 'trainer_courses';
    protected $fillable = [
        'countHours',
        'courseProgress',
        'trainer_id',
        'course_id'
      ];
      public function course()
      {
          return $this->belongsTo(Course::class, 'course_id');
      }

      public function trainer()
      {
          return $this->belongsTo(Trainer::class, 'trainer_id');
      }
}
