<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taplicacion extends Model
{
    use HasFactory;
    public function pestudio(){
        return $this->belongsTO(Pestudio::class, 'pestudio_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
