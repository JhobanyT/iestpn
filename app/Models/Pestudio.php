<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pestudio extends Model
{
    protected $table = 'pestudios';

    public function autores()
    {
        return $this->hasMany(Autor::class, 'pestudio_id');
    }
}
