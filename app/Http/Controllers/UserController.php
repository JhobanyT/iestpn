<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->role == 'admin'){
            $users = User::all();
            return view ('user.index')->with('users',$users);
        } else{
            return redirect()->to('/');
        }
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
        $users = User::findOrFail($id);
        return view('user.show', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {


        if(auth()->user()->role == 'admin'){
            // Obtener el usuario por su ID desde la base de datos
            $users = User::findOrFail($id);

            // Pasar el usuario a la vista de edición
            return view('user.edit', compact('users'));
        } else{
            return redirect()->to('/');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario de edición
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'role' => 'required|string|max:100',
        ]);

        // Obtener el usuario por su ID desde la base de datos
        $users = User::findOrFail($id);

        // Actualizar los datos del usuario con los datos del formulario
        $users->name = $request->input('name');
        $users->email = $request->input('email');
        $users->role = $request->input('role');

        // Guardar los cambios en la base de datos
        $users->save();

        // Redireccionar a la vista de detalles del usuario actualizado
        return redirect()->route('usuarios.index', $users->id)
                         ->with('success', 'Usuario actualizado exitosamente.');
    }

    /*
    Cambiar y actualizar contraseña
    */
    public function cambiarContrasena($id)
    {
        $user = User::findOrFail($id);
        return view('user.cambiar_contrasena', compact('user'));
    }

    public function actualizarContrasena(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:5|confirmed',
        ]);

        $user = User::findOrFail($id);

        // Actualizar la contraseña del usuario con la nueva contraseña
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return redirect()->route('usuarios.show', $user->id)
                        ->with('success', 'Contraseña del usuario ' . $user->name . ' actualizada exitosamente.');
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if(auth()->user()->role == 'admin'){
            $users = User::findOrFail($id);
            $users->delete();

            return redirect()->route('usuarios.index')
            ->with('success', 'El usuario ha sido eliminado exitosamente.');
        } else{
            return redirect()->to('/');
        }
    }
}
