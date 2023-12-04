<?php

namespace App\Http\Controllers;

use App\Models\Pestudio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProgramaEstudioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()){
            $programaEstudios = Pestudio::all();
            return view ('programaEstudios.index')->with('programaEstudios',$programaEstudios);
        } else{
            return redirect()->to('/');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(auth()->user()){
            $pestudio = Pestudio::all();
            return view ('programaEstudios.create')->with('pestudio',$pestudio)
                ->with('success', 'El programa de estudios ha sido registrado exitosamente.');

        } else{
            return redirect()->to('/');
        }

        //return view ('programaEstudios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(auth()->user()){
            $pestudios = new Pestudio();
            $pestudios->nombre = $request->get('nombre');
            $pestudios->save();
            return redirect('/programaEstudios')
            ->with('success', 'El programa de estudios ha sido guardado exitosamente.');
        } else{
            return redirect()->to('/');
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Pestudio $pestudio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if(auth()->user()){
            $pestudio = Pestudio::find($id);
            return view('programaEstudios.edit')->with('pestudio',$pestudio)
            ->with('success', 'El programa de estudios ha sido actualizado exitosamente.');

        } else{
            return redirect()->to('/');
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $pestudio = Pestudio::find($id);
        $pestudio->nombre = $request->get('nombre');

        $pestudio->save();

        return redirect('/programaEstudios')
            ->with('success', 'El programa de estudios ha sido actualizado exitosamente.');
    }
}
