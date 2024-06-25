<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name','expierd_date','quntity','status','description', 'type_id','category_id'];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
