<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function create(){
        return view('auth.register');
    }

    public function store(){
        $this->validate(request(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'role' => 'required',
        ]);


        $user = User::create(request(['name', 'email', 'password', 'role']));
        auth()->login($user);
        return redirect()->to('/register')->with('success', 'Usuario registrado exitosamente.');


    }
}
