<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Pestudio;
use App\Models\TrabajoAutor;
use Illuminate\Support\Facades\Log;
use App\Models\Taplicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
use Exception;

class TrabajoAplicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($user = auth()->user()) {
            // Si es admin, muestra todos los trabajos de aplicación con sus autores
            $query = Taplicacion::with('autores.pestudio');
            // Verificar si se ingresó un término de búsqueda
            $searchTerm = $request->input('q');
            $fecha = $request->input('fecha');

            if ($searchTerm || $fecha) {
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
            } else {
                // Si no se ingresó ningún término o fecha, no se realiza ninguna búsqueda y se obtienen todos los trabajos de aplicación
                $query->get();
            }

            // Ordenar por fecha de creación descendente
            $query->orderByDesc('created_at');
            // Obtener los resultados de la búsqueda
            $trabajoAplicacion = $query->paginate(5);

            // Agregar los parámetros de búsqueda a las URL de los botones de paginación
            $trabajoAplicacion->appends(['q' => $searchTerm, 'fecha' => $fecha]);

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

            return view('trabajoAplicacion.index', compact('trabajoAplicacion', 'searchTerm', 'fecha'));
        } else {
            return redirect()->to('/');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()) {
            $pestudios = Pestudio::all();
            // dd($pestudios); // Verifica los datos aquí
            return view('trabajoAplicacion.create', compact('pestudios'));
        } else {
            return redirect()->to('/');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'titulo' => 'required',
                'resumen' => 'required',
                'archivo' => [
                    'required',
                    'file',
                    'mimes:pdf',
                    'max:10000',
                    Rule::unique('taplicacions', 'archivo'),
                ],
                'autors' => 'nullable|array|min:1',
                'autors.*' => 'required_with:autors|string|max:255',
                'pestudio_id' => 'nullable|array|min:1',
                'pestudio_id.*' => 'required_with:pestudio_id|numeric|exists:pestudios,id',
            ]);

            $archivo = $request->file('archivo');
            $archivoNombre = $archivo->getClientOriginalName();
            $archivoRuta = $archivo->storeAs('archivos', $archivoNombre, 'public');

            // Verificar si el archivo ya existe en la base de datos
            $archivoExiste = Taplicacion::where('archivo', $archivoRuta)->exists();

            if ($archivoExiste) {
                return redirect()->route('trabajoAplicacion.create')->withErrors(['archivo' => 'El archivo con ese nombre ya existe. Por favor, elige otro archivo diferente.'])->withInput();
            }

            foreach ($request->autors as $key => $autorNombre) {
                $autorExistente = Autor::where('nombre', $autorNombre)
                    ->where('pestudio_id', '!=', $request->pestudio_id[$key])
                    ->first();

                if ($autorExistente) {
                    return redirect()->route('trabajoAplicacion.create')
                        ->withErrors(['autors.' . $key => "El autor '$autorNombre' ya pertenece a otro programa de estudios."])
                        ->withInput();
                }
            }

            $trabajoAplicacion = new Taplicacion();
            $trabajoAplicacion->titulo = $request->titulo;
            $trabajoAplicacion->resumen = $request->resumen;
            $trabajoAplicacion->archivo = $archivoRuta;
            $trabajoAplicacion->user_id = auth()->user()->id;

            if (count($request->autors) >= 2) {
                $programasDeEstudio = collect($request->pestudio_id)->unique();
                $trabajoAplicacion->tipo = $programasDeEstudio->count() > 1 ? 'Interdisciplinario' : 'Normal';
            } else {
                $trabajoAplicacion->tipo = 'Normal';
            }

            $trabajoAplicacion->save();

            // Guardar autores relacionados
            foreach ($request->autors as $key => $autorNombre) {
                $autor = Autor::firstOrCreate([
                    'nombre' => $autorNombre,
                    'pestudio_id' => $request->pestudio_id[$key],
                ]);

                // Agregar autor a la relación autores() del Taplicacion
                $trabajoAplicacion->autores()->attach($autor->id);
            }

            Session::flash('success', 'El trabajo de aplicación ha sido creado exitosamente.');
            return redirect()->route('trabajoAplicacion.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());

            // Redirigir a la vista de creación con un mensaje de error
            return redirect()->route('trabajoAplicacion.index')
                ->with('error', 'Ha ocurrido un error en el servidor. Por favor, inténtalo de nuevo más tarde.');
        }
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

        return view('trabajoAplicacion.show', compact('taplicacion', 'mostCommonProgramaEstudios'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if(auth()->user()){
            $taplicacion = TAplicacion::findOrFail($id);
            $pestudios = Pestudio::all();
            return view('trabajoAplicacion.edit', compact('taplicacion', 'pestudios'));
        } else{
            return redirect()->to('/');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $taplicacion = TAplicacion::findOrFail($id);

    $request->validate([
        'titulo' => 'required|max:255',
        'resumen' => 'required',
        'archivo' => [
            'nullable',
            'file',
            'mimes:pdf',
            'max:10000',
            Rule::unique('taplicacions')->ignore($taplicacion->id),
        ],
        'autors' => 'required|array|min:1',
        'autors.*' => 'required|string|max:255',
        'pestudio_id' => 'required|array|min:1',
        'pestudio_id.*' => 'required|exists:pestudios,id',
    ]);

    // Verificar si el archivo ya existe en la base de datos
    if ($request->hasFile('archivo')) {
        $archivo = $request->file('archivo');
        $archivoNombre = $archivo->getClientOriginalName();
        $archivoRuta = $archivo->storeAs('archivos', $archivoNombre, 'public');

        $archivoExiste = Taplicacion::where('archivo', $archivoRuta)->where('id', '!=', $taplicacion->id)->exists();
        if ($archivoExiste) {
            return redirect()->route('trabajoAplicacion.edit', $id)
                ->withErrors(['archivo' => 'El archivo con ese nombre ya existe. Por favor, elige otro archivo diferente.'])
                ->withInput();
        }

        // Eliminar el archivo antiguo si existe
        if ($taplicacion->archivo) {
            Storage::delete('public/' . $taplicacion->archivo);
        }

        $taplicacion->archivo = $archivoRuta;
    }

    // Actualizar el título y resumen
    $taplicacion->titulo = $request->titulo;
    $taplicacion->resumen = $request->resumen;

    // Guardar los cambios en la base de datos
    $taplicacion->save();

    // Actualizar los autores relacionados
    $autoresActualizados = [];

    foreach ($request->autors as $key => $autorNombre) {
        $autorExistente = Autor::where('nombre', $autorNombre)->first();

        if (!$autorExistente) {
            // Si el autor no existe, crear uno nuevo
            $autorExistente = Autor::create([
                'nombre' => $autorNombre,
                'pestudio_id' => $request->pestudio_id[$key],
            ]);
        } elseif ($autorExistente->pestudio_id != $request->pestudio_id[$key]) {
            // Si el autor existe pero pertenece a otro programa de estudios, mostrar error
            return redirect()->route('trabajoAplicacion.edit', $id)
                ->withErrors(['autors.' . $key => "El autor '$autorNombre' ya pertenece a otro programa de estudios."])
                ->withInput();
        }

        $autoresActualizados[] = $autorExistente->id;
    }

    // Sincronizar la relación autores() del Taplicacion con los autores actualizados
    $taplicacion->autores()->sync($autoresActualizados);

    // Eliminar los autores no asociados a ningún registro
    $autoresViejos = Autor::whereDoesntHave('trabajosDeAplicacion')->get();

    foreach ($autoresViejos as $autorViejo) {
        $autorViejo->delete();
    }

    $programasDeEstudio = $taplicacion->autores->pluck('pestudio_id')->unique();
    $taplicacion->tipo = $programasDeEstudio->count() > 1 ? 'Interdisciplinario' : 'Normal';
    $taplicacion->save();

    return redirect()->route('trabajoAplicacion.index')->with('success', 'La aplicación de trabajo se actualizó correctamente.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (auth()->user()) {
            $taplicacion = Taplicacion::findOrFail($id);

            // Eliminar los registros relacionados en la tabla trabajo_autors
            $taplicacion->autores()->detach();

            // Eliminar el archivo si existe
            if ($taplicacion->archivo) {
                Storage::delete('public/' . $taplicacion->archivo);
            }

            // Eliminar el trabajo de aplicación
            $taplicacion->delete();

            // Eliminar los autores que ya no tienen relación con ningún otro trabajo de aplicación
            $autoresViejos = Autor::whereDoesntHave('trabajosDeAplicacion')->get();
            foreach ($autoresViejos as $autorViejo) {
                $autorViejo->delete();
            }

            return redirect()->route('trabajoAplicacion.index')
                ->with('success', 'El trabajo de aplicación ha sido eliminado exitosamente.');
        } else {
            return redirect()->to('/');
        }
    }

}
