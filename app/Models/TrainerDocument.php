<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Trainer;
class TrainerDocument extends Model
{
    use HasFactory;
    protected $table = 'trainer_documents';
    protected $fillable = [
        'trainer_id',
        'image',
        'file_pdf',
    ];
    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }
}
