<?php

namespace App\Http\Controllers;

use App\Models\Taplicacion;
use App\Models\Autor;
use App\Models\Pestudio;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Exception;

class PublicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $trabajoAplicacion = Taplicacion::all();

        $query = Taplicacion::with('autores.pestudio');

            // Verificar si se ingresó un término de búsqueda
            $searchTerm = $request->input('q');
            $fecha = $request->input('fecha');
            $filtroAnio = $request->input('anio');
            $selectedPestudios = $request->input('pestudio', []);
            $selectedTipos = $request->input('tipo', []);

            if ($searchTerm || $fecha || $filtroAnio || !empty($selectedPestudios) || !empty($selectedTipos)) {
                // Verificar si hay separador ";" para buscar autores
                if (str_contains($searchTerm, ';')) {
                    $autoresArray = explode(';', $searchTerm);

                    $query->whereHas('autores', function ($query) use ($autoresArray) {
                        $query->whereIn('nombre', $autoresArray);
                    });
                } else {
                    // Realizar la búsqueda por término en el título, autor, nombre del programa de estudio y resumen
                    $query->where(function ($query) use ($searchTerm) {
                        $query->where('titulo', 'like', '%' . $searchTerm . '%')
                            ->orWhereHas('autores', function ($query) use ($searchTerm) {
                                $query->where('nombre', 'like', '%' . $searchTerm . '%');
                            })
                            ->orWhereHas('autores.pestudio', function ($query) use ($searchTerm) {
                                $query->where('nombre', 'like', '%' . $searchTerm . '%');
                            })
                            ->orWhere('tipo', 'like', '%' . $searchTerm . '%')
                            ->orWhere('resumen', 'like', '%' . $searchTerm . '%');
                    });
                }

                // Verificar si se ingresó una fecha de búsqueda
                if ($fecha) {
                    // Realizar la búsqueda por fecha de publicación
                    $query->whereDate('created_at', $fecha);
                }
                if ($filtroAnio) {
                    // Realizar la búsqueda por año de publicación
                    $query->whereYear('created_at', $filtroAnio);
                }

                if (!empty($selectedPestudios)) {
                    $query->whereHas('autores.pestudio', function ($query) use ($selectedPestudios) {
                        $query->whereIn('nombre', $selectedPestudios);
                    });
                }

                if (!empty($selectedTipos)) {
                    $query->whereIn('tipo', $selectedTipos);
                }


            } else {
                // Si no se ingresó ningún término o fecha, no se realiza ninguna búsqueda y se obtienen todos los trabajos de aplicación
                $query->get();
            }

            // Obtener los resultados de la búsqueda
            $trabajoAplicacion = $query->paginate(5);

            // Agregar los parámetros de búsqueda a las URL de los botones de paginación
            $trabajoAplicacion->appends(['q' => $searchTerm, 'fecha' => $fecha, 'anio' => $filtroAnio, 'tipo' => $selectedTipos, 'pestudio' => $selectedPestudios]);

            // Obtener el nombre del programa de estudios más común para cada trabajo de aplicación
            foreach ($trabajoAplicacion as $trabajo) {
                $autores = $trabajo->autores;
                $programasDeEstudio = $autores->pluck('pestudio')->filter(function ($value) {
                    return !is_null($value);
                });

                $programaEstudiosMasComun = 'Sin programa de estudios'; // Valor predeterminado si no se encuentra ningún programa de estudios

                if ($programasDeEstudio->count() > 0) {
                    // Contar la cantidad de veces que aparece cada programa de estudios
                    $programasCount = $programasDeEstudio->countBy('id')->sortDesc();

                    // Obtener el ID del programa de estudios más común (el primero en caso de empates)
                    $idMasComun = $programasCount->keys()->first();

                    // Buscar el programa de estudios correspondiente al ID obtenido
                    $programaEstudiosMasComun = $programasDeEstudio->where('id', $idMasComun)->first()->nombre;
                }

                $trabajo->programaEstudiosMasComun = $programaEstudiosMasComun;
            }
            $availableYears = Taplicacion::distinct()
                ->orderByDesc('created_at')
                ->pluck('created_at')
                ->map(function ($date) {
                    return $date->format('Y');
                })
                ->unique();
                $availablePestudios = Pestudio::distinct()->pluck('nombre');
                $availableTipos = Taplicacion::distinct()->pluck('tipo');


                return view('publics.index', compact('trabajoAplicacion', 'searchTerm', 'fecha', 'availableYears', 'filtroAnio','availablePestudios', 'availableTipos', 'selectedPestudios', 'selectedTipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $taplicacion = Taplicacion::findOrFail($id);
        $taplicacion->load('autores.pestudio'); // Cargar los modelos relacionados 'autores' y 'pestudio' al mismo tiempo

        // Obtener los autores y contar cuántos pertenecen a cada programa de estudios
        $programasCount = $taplicacion->autores->groupBy('pestudio_id')->map->count();

        // Obtener el id del programa de estudios más común
        $mostCommonProgramaEstudiosId = $programasCount->sortDesc()->keys()->first();

        // Obtener el modelo del programa de estudios más común
        $mostCommonProgramaEstudios = $taplicacion->autores->firstWhere('pestudio_id', $mostCommonProgramaEstudiosId)->pestudio;

        return view('publics.show', compact('taplicacion', 'mostCommonProgramaEstudios'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Taplicacion $taplicacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Taplicacion $taplicacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Taplicacion $taplicacion)
    {
        //
    }
}
