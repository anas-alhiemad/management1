<?php

namespace App\Models;
use App\Models\Beneficiary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class foreignLanguages extends Model
{
    use HasFactory;
    protected $table = 'foreign_languages';
    protected $fillable = [
        'beneficiary_id',
        'namelanguage',
        'level',
    ];

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }
}
