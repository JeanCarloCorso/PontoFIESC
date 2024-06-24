<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('usuario', $request->usuario)->first();

        if ($user && Hash::check($request->senha, $user->senha)) {
            Auth::login($user);

            return redirect()->route('ponto.view');
        } 
        return back()->withErrors(['usuario' => 'Usuário ou senha incorretos.']);
    }

    // Método para logout
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
