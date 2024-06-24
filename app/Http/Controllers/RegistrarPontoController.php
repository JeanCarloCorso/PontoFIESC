<?php

namespace App\Http\Controllers;

use App\Models\Horario;
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

            $mensagem = $this->verificarRegras($novoRegistro);

            return redirect()->route('ponto.view')->with('mensagem', $mensagem);
        }
        return redirect('/');
    }

    private function verificarRegras($novoRegistro)
    {
        $user = Auth()->user();
        $horarios = Horario::where('matricula', $user->matricula)->get();

        if (!$horarios) {
            return 'Não foi possível verificar as regras. Horário não cadastrado.';
        }

        // Calcula o total de horas trabalhadas no dia até o momento
        $totalHorasTrabalhadas = $this->calcularTotalHorasTrabalhadas($user->matricula, $novoRegistro->data);

        // Regra 10.6 - Verifica se trabalhou mais que o total de horário cadastrado
        if ($totalHorasTrabalhadas > $this->calcularTotalHorasHorario($horarios)) {
            return 'Você trabalhou mais que o total de horas cadastrado no seu horário.';
        }

        // Regra 10.7 - Verifica se trabalhou mais de 10h no dia
        if ($totalHorasTrabalhadas > 10 * 60) {
            return 'Você trabalhou mais de 10 horas hoje. Será gerado um Termo de Ajuste de Conduta (TAC).';
        }

        if ($this->verificarIntervaloEntreJornadasMenorQue11h($user->matricula, $novoRegistro->data)) {
            return 'Você fez um intervalo entre jornadas menor que 11 horas. Será gerado um Termo de Ajuste de Conduta (TAC).';
        }

        // Regra 10.9 - Verifica intervalo inter jornada menor que 1h
        if ($this->verificarIntervaloInterJornadaMenorQue1h($user->matricula, $novoRegistro->data)) {
            return 'Você fez um intervalo inter jornada menor que 1 hora. Será gerado um Termo de Ajuste de Conduta (TAC).';
        }

        return 'Ponto registrado com sucesso.';
    }

    private function verificarIntervaloEntreJornadasMenorQue11h($matricula, $data)
    {
        // Busca todos os registros de ponto para a matrícula e data especificada
        $registros = RegistroPonto::where('matricula', $matricula)
            ->whereDate('data', $data)
            ->orderBy('data', 'asc')
            ->orderBy('hora', 'asc')
            ->get();

        $ultimaSaida = null;
        foreach ($registros as $registro) {
            if ($registro->tipo == 'entrada' && $ultimaSaida !== null) {
                // Calcula o intervalo entre a última saída e a entrada atual
                $intervalo = Carbon::parse($registro->data . ' ' . $registro->hora)->diffInHours($ultimaSaida);

                // Verifica se o intervalo é menor que 11 horas
                if ($intervalo < 11) {
                    return true;
                }
            }

            // Atualiza $ultimaSaida para a última saída registrada
            if ($registro->tipo == 'saida') {
                $ultimaSaida = Carbon::parse($registro->data . ' ' . $registro->hora);
            }
        }

        return false;
    }

    private function verificarIntervaloInterJornadaMenorQue1h($matricula, $data)
    {
        // Busca todos os registros de ponto para a matrícula e data especificada
        $registros = RegistroPonto::where('matricula', $matricula)
            ->whereDate('data', $data)
            ->orderBy('data', 'asc')
            ->orderBy('hora', 'asc')
            ->get();

        $ultimaSaida = null;
        foreach ($registros as $registro) {
            if ($registro->tipo == 'entrada' && $ultimaSaida !== null) {
                // Calcula o intervalo entre a última saída e a entrada atual
                $intervalo = Carbon::parse($registro->data . ' ' . $registro->hora)->diffInMinutes($ultimaSaida);

                // Verifica se o intervalo é menor que 60 minutos (1 hora)
                if ($intervalo < 60) {
                    return true;
                }
            }

            // Atualiza $ultimaSaida para a última saída registrada
            if ($registro->tipo == 'saida') {
                $ultimaSaida = Carbon::parse($registro->data . ' ' . $registro->hora);
            }
        }

        return false;
    }

    private function calcularTotalHorasTrabalhadas($matricula, $data)
    {
        $registros = RegistroPonto::where('matricula', $matricula)
            ->whereDate('data', $data)
            ->get();

        $totalMinutos = 0;
        $ultimaEntrada = null;

        foreach ($registros as $registro) {
            $entrada = Carbon::parse($registro->data . ' ' . $registro->hora);

            if ($ultimaEntrada !== null && $registro->tipo == 'saida') {
                $totalMinutos += $entrada->diffInMinutes($ultimaEntrada);
            }

            $ultimaEntrada = $entrada;
        }
        return $totalMinutos;
    }

    private function calcularTotalHorasHorario($horarios)
    {
        $minutosTrabalhados = 0;

        foreach($horarios as $horario){

            $entrada1 = $horario->entrada1;
            $saida1 = $horario->saida1;
            $entrada2 = $horario->entrada2;
            $saida2 = $horario->saida2;
    
            if ($entrada1 && $saida1) {
                $diff1 = $saida1->diffInMinutes($entrada1);
                $minutosTrabalhados += $diff1;
            }
    
            if ($entrada2 && $saida2) {
                $diff2 = $saida2->diffInMinutes($entrada2);
                $minutosTrabalhados += $diff2;
            }
        }
        return $minutosTrabalhados;
    }
}
