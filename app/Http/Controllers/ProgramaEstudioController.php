<?php

namespace App\Http\Controllers;

use App\Models\Pestudio;
use Illuminate\Http\Request;

class ProgramaEstudioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programaEstudios = Pestudio::all();
        return view ('programaEstudios.index')->with('programaEstudios',$programaEstudios);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('programaEstudios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pestudios = new Pestudio();
        $pestudios->nombre = $request->get('nombre');
        $pestudios->save();

        return redirect('/programaEstudios');
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
        $pestudio = Pestudio::find($id);
        return view('programaEstudios.edit')->with('pestudio',$pestudio);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pestudio = Pestudio::find($id);
        $pestudio->nombre = $request->get('nombre');

        $pestudio->save();

        return redirect('/programaEstudios');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pestudio = Pestudio::find($id);
        $pestudio->delete();

        return redirect('/programaEstudios');
    }
}
