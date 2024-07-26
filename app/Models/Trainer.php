<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TrainerCourse;
class Trainer extends Model
{
    use HasFactory;

    protected $table = 'trainers';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'specialty',
        'description',
    ];

    public function trainerCourse() {
        return $this->hasMany(TrainerCourse::class,'trainer_id');
    }
}
