<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taplicacion extends Model
{
    use HasFactory;
   
    protected $table = 'taplicacions';
    protected $fillable = ['titulo', 'resumen', 'archivo', 'tipo'];

    // Relación con autores
    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'trabajo_autors', 'trabajo_id', 'autor_id')
                    ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Atributo calculado para determinar si es interdisciplinario
    public function getInterdisciplinarioAttribute()
    {
    $autores = $this->autores;

    // Si hay al menos dos autores y al menos dos programas de estudio diferentes, es interdisciplinario
    if ($autores->count() >= 2) {
        $programasDeEstudio = $autores->pluck('pestudio_id')->unique();
        return $programasDeEstudio->count() > 1;
    }

    return false; // Si solo hay un autor, se considera normal
    }
}
