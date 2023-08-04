<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrabajoAutor extends Model
{
    use HasFactory;

    protected $table = 'trabajo_autors';
    protected $fillable = ['trabajo_id', 'autor_id'];

    // Relación con el trabajo de aplicación
    public function trabajoDeAplicacion()
    {
        return $this->belongsTo(Taplicacion::class, 'trabajo_id');
    }

    // Relación con el autor
    public function autor()
    {
        return $this->belongsTo(Autor::class, 'autor_id');
    }
}