<?php

namespace App\Http\Controllers;

use App\Models\Pestudio;
use App\Models\Taplicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class TrabajoAplicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trabajoAplicacion = Taplicacion::all();
        return view ('trabajoAplicacion.index')->with('trabajoAplicacion',$trabajoAplicacion);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pestudios = Pestudio::all();
        return view('trabajoAplicacion.create', compact('pestudios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required',
            'autor' => 'required',
            'pestudio_id' => 'required',
            'resumen' => 'required',
            'archivo' => 'required|file|mimes:pdf|max:10000',
        ]);

        $archivo = $request->file('archivo');
        $archivoNombre = $archivo->getClientOriginalName();
        $archivoRuta = $archivo->storeAs('archivos', $archivoNombre, 'public');

        $trabajoAplicacion = new Taplicacion();
        $trabajoAplicacion->titulo = $request->titulo;
        $trabajoAplicacion->autor = $request->autor;
        $trabajoAplicacion->pestudio_id = $request->pestudio_id;
        $trabajoAplicacion->resumen = $request->resumen;
        $trabajoAplicacion->archivo = $archivoRuta;
        $trabajoAplicacion->save();

        Session::flash('success', 'El trabajo de aplicación ha sido creado exitosamente.');
        return redirect()->route('trabajoAplicacion.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $taplicacion = Taplicacion::findOrFail($id);
        $taplicacion->load('pestudio'); // Cargar el modelo relacionado 'pestudio'

        return view('trabajoAplicacion.show', compact('taplicacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $taplicacion = TAplicacion::findOrFail($id);
        $pestudios = Pestudio::all();

        return view('trabajoAplicacion.edit', compact('taplicacion', 'pestudios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $taplicacion = TAplicacion::findOrFail($id);

        // Validar los campos de entrada
        $request->validate([
            'titulo' => 'required',
            'autor' => 'required',
            'pestudio_id' => 'required',
            'resumen' => 'required',
            'archivo' => 'nullable|mimes:pdf|max:10000', // Actualizar la validación del archivo
        ]);

        // Actualizar los campos del trabajo de aplicación
        $taplicacion->titulo = $request->titulo;
        $taplicacion->autor = $request->autor;
        $taplicacion->pestudio_id = $request->pestudio_id;
        $taplicacion->resumen = $request->resumen;

        // Verificar si se seleccionó un nuevo archivo
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');

            // Eliminar el archivo antiguo si existe
            if ($taplicacion->archivo) {
                Storage::delete('public/' . $taplicacion->archivo);
            }

            // Guardar el nuevo archivo con el nombre original
            $path = $archivo->storeAs('archivos', $archivo->getClientOriginalName(), 'public');
            $taplicacion->archivo = $path;
        }

        // Guardar los cambios en el trabajo de aplicación
        $taplicacion->save();

        return redirect()->route('trabajoAplicacion.index', $taplicacion->id)
            ->with('success', 'El trabajo de aplicación ha sido actualizado exitosamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $taplicacion = TAplicacion::findOrFail($id);

        // Eliminar el archivo si existe
        if ($taplicacion->archivo) {
            Storage::delete('public/' . $taplicacion->archivo);
        }

        // Eliminar el registro del trabajo de aplicación
        $taplicacion->delete();

        return redirect()->route('trabajoAplicacion.index')
            ->with('success', 'El trabajo de aplicación ha sido eliminado exitosamente.');
    }
}
