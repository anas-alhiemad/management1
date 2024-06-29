<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'requsetPending',
        'status',
        'type'
    ];

    protected $casts = [
        'requsetPending' => 'array',
    ];
}
