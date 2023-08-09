<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function create(){
        if(auth()->user()->role == 'admin'){
            return view('auth.register');
        } elseif(auth()->user()->role == 'administrador'){
            return redirect()->to('/trabajoAplicacion');
        } else{
            return redirect()->to('/');
        }
    }

    public function store(){
        $this->validate(request(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'role' => 'required',
        ]);


        $user = User::create(request(['name', 'email', 'password', 'role']));
        return redirect()->to('/usuarios')->with('success', 'Se ha registrado un Nuevo usuario.');
    }
}
