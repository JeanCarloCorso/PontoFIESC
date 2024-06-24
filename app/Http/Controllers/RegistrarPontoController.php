<?php

namespace App\Http\Controllers;

use App\Models\RegistroPonto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrarPontoController extends Controller
{
    public function pontoView(){
        if(Auth()->check())
        {
            $registros = RegistroPonto::where('matricula', Auth::user()->matricula)
                ->whereDate('data', Carbon::today())
                ->get();

            return view('registroPonto', compact('registros'));
        }
        return redirect('/');
    }

    public function BatePonto(){
        if(Auth()->check())
        {
            $ultimoRegistro = RegistroPonto::where('matricula', Auth::user()->matricula)
            ->latest('id')
            ->first();

            $tipo = $ultimoRegistro && $ultimoRegistro->tipo == 'entrada' ? 'saida' : 'entrada';

            $novoRegistro = new RegistroPonto();
            $novoRegistro->matricula = Auth::user()->matricula;
            $novoRegistro->data = Carbon::today(); 
            $novoRegistro->hora = Carbon::now()->toTimeString();
            $novoRegistro->tipo = $tipo;
            $novoRegistro->save();

            return redirect()->route('ponto.view');
        }
        return redirect('/');
    }
}
