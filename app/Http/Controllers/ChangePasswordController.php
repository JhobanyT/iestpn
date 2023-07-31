<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class ChangePasswordController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('auth.changeme');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'La contraseña actual no coincide con nuestros registros.']);
        }
        if ($request->new_password !== $request->new_password_confirmation) {
            return redirect()->back()->withErrors(['new_password_confirmation' => 'La confirmación de la nueva contraseña no coincide.']);
        }


        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->to('/trabajoAplicacion')->with('success', 'Contraseña cambiada exitosamente.');
    }
}

