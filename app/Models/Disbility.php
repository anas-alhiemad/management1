<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Beneficiary;
class Disbility extends Model
{
    use HasFactory;
    protected $table = 'disbilities';
    protected $fillable = [
        'beneficiary_id',
        'nameDisbility',
        'rateDisbility',
    ];

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }
}
