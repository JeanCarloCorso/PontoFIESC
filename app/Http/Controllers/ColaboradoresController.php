<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Funcoes;
use App\Models\Horario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ColaboradoresController extends Controller
{
    public function ColaboradoresView(){
        if(Auth()->check() && Auth()->user()->tipo == 'administrador')
        {
            $users = User::all();
            return view('listaColaboradores', compact('users'));
        }
        return redirect('/');
    }

    public function NovoColaborador(){
        if(Auth()->check() && Auth()->user()->tipo == 'administrador')
        {
            $funcoes = Funcoes::all();
            $cargos = Cargo::all();
            $criacao = true;
            return view('cadastroColaborador', compact('funcoes', 'cargos', 'criacao'));
        }
        return redirect('/');
    
    }
    public function EditarColaborador($matricula){
        if(Auth()->check() && Auth()->user()->tipo == 'administrador')
        {
            $colaborador = User::where('matricula', $matricula)->first();

            $cpf = $colaborador->cpf;
            $cpfFormatado = substr_replace($cpf, '.', 3, 0);
            $cpfFormatado = substr_replace($cpfFormatado, '.', 7, 0);
            $cpfFormatado = substr_replace($cpfFormatado, '-', 11, 0);
            $colaborador->cpf = $cpfFormatado;

            $horarios = Horario::where('matricula', $matricula)->get();
            $funcoes = Funcoes::all();
            $cargos = Cargo::all();
            $criacao = false;
            
            return view('cadastroColaborador', compact('funcoes', 'cargos', 'criacao', 'colaborador', 'horarios'));
        }
        return redirect('/');
    }

    public function SalvaEdicaoColaborador(Request $request, $matricula)
    {
        $this->ValidaDados($request, false);
        
        DB::transaction(function () use ($request, $matricula) {
            // Atualiza o usuário
            
            $user = User::where('matricula', $matricula)->firstOrFail();
            $user->update([
                'nome' => $request->nome,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'dataNascimento' => $request->dataNascimento,
                'dataAdmissao' => $request->dataAdmissao,
                'funcoes_id' => $request->funcao,
                'cargo_id' => $request->cargo,
                'tipo' => $request->tipo,
                'dataRecisao' => $request->dataRecisao,
            ]);
            
            // Atualiza os horários
            foreach ($request->horarios as $diaSemana => $horario) {
                // Encontra o registro existente ou cria um novo
                $existingHorario = Horario::where('matricula', $matricula)->where('DiaSemana', $diaSemana)->first();
    
                if ($horario['entrada1'] === null) {
                    // Se entrada1 for null, exclui o registro se ele existir
                    if ($existingHorario) {
                        $existingHorario->delete();
                    }
                } else {
                    if ($existingHorario) {
                        // Atualiza o registro existente
                        $existingHorario->update([
                            'entrada1' => $horario['entrada1'],
                            'saida1' => $horario['saida1'] ?? null,
                            'entrada2' => $horario['entrada2'] ?? null,
                            'saida2' => $horario['saida2'] ?? null,
                        ]);
                    } else {
                        // Cria um novo registro
                        Horario::create([
                            'matricula' => $user->matricula,
                            'DiaSemana' => $diaSemana,
                            'entrada1' => $horario['entrada1'],
                            'saida1' => $horario['saida1'] ?? null,
                            'entrada2' => $horario['entrada2'] ?? null,
                            'saida2' => $horario['saida2'] ?? null,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('colaboradores.view');

    }

    public function VerificarCPF(rEQUEST $request)
    {
        if(Auth()->check() && Auth()->user()->tipo == 'administrador')
        {
            
            $cpf = $request->get('cpf');
            $user = User::where('cpf', $cpf)->first();

            if ($user) {
                return response()->json(['exists' => true]);
            } else {
                return response()->json(['exists' => false]);
            }
        }
        return redirect('/');
    }

    public function CadastrarNovoColaborador(Request $request)
    {
        $this->ValidaDados($request, true);
        
        DB::transaction(function () use ($request) {
            $nomeParts = explode(' ', trim($request->nome));
            $primeiroNome = Str::lower($nomeParts[0]);
            $ultimoNome = Str::lower(end($nomeParts)); 

            $usuario = $this->gerarUsuarioUnico($primeiroNome, $ultimoNome);

            // Cria o usuário
            $user = User::create([
                'cpf' => str_replace(['.', '-'], '', $request->cpf),
                'nome' => $request->nome,
                'usuario' => $usuario,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'dataNascimento' => $request->dataNascimento,
                'dataAdmissao' => $request->dataAdmissao,
                'dataRecisao' => $request->dataRecisao,
                'funcoes_id' => $request->funcao,
                'cargo_id' => $request->cargo,
                'senha' => bcrypt('123456789'),
                'tipo' => $request->tipo,
            ]);
    
            // Cria os horários
            foreach ($request->horarios as $diaSemana => $horario) {
                if (!is_null($horario['entrada1']) && !is_null($horario['saida1'])) {
                    Horario::create([
                        'matricula' => $user->matricula,
                        'DiaSemana' => $diaSemana,
                        'entrada1' => $horario['entrada1'],
                        'saida1' => $horario['saida1'],
                        'entrada2' => $horario['entrada2'] ?? null,
                        'saida2' => $horario['saida2'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('colaboradores.view');
    }

    private function ValidaDados($request, $criacao)
    {
        if($criacao){
            $request->validate([
                'cpf' => 'required',
            ], [
                'cpf.required' => 'O campo CPF é obrigatório.',
            ]);
        }
        $request->validate([ 
            'telefone' => 'required', 
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'dataNascimento' => 'required|date',
            'dataAdmissao' => 'required|date',
            'funcao' => 'required',
            'cargo' => 'required',
            // Regra 2.9.1: Entradas não podem ser maiores que as respectivas saídas
            'horarios.*.entrada1' => 'nullable|date_format:H:i|before_or_equal:horarios.*.saida1',
            'horarios.*.entrada2' => 'nullable|date_format:H:i|before_or_equal:horarios.*.saida2|required_with:horarios.*.saida2',
            // Regra 2.9.2: Saídas não podem ser menores que as respectivas entradas
            'horarios.*.saida1' => 'nullable|date_format:H:i|after_or_equal:horarios.*.entrada1',
            'horarios.*.saida2' => 'nullable|date_format:H:i|after_or_equal:horarios.*.entrada2',
            // Regra 2.9.3: Intervalo mínimo de 1 hora entre a primeira saída e a segunda entrada
            'horarios.*.entrada2' => 'nullable|date_format:H:i|after_or_equal:horarios.*.saida1|after:horarios.*.saida1|required_with:horarios.*.saida2',
            // Regra 2.9.4: Intervalo mínimo de 11 horas entre a primeira entrada e a segunda saída do dia anterior
            'horarios.*.entrada1' => 'nullable|date_format:H:i|after_or_equal:horarios_anterior.*.saida2|before:13:00',
            // Regra 2.9.5: Total de horas semanais não pode ser mais que 44 horas
            'horarios' => ['required', 'array', function ($attribute, $value, $fail) {
                $totalHorasSemanais = $this->calcularTotalHorasSemanais($value);
                if ($totalHorasSemanais > 44) {
                    $fail('O total de horas semanais não pode ser mais que 44 horas.');
                }
            }],
        ], [
            'telefone.required' => 'O campo telefone é obrigatório.',
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O campo nome deve ser um texto.',
            'nome.max' => 'O campo nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email informado é inválido.',
            'email.max' => 'O campo email não pode ter mais de 255 caracteres.',
            'dataNascimento.required' => 'O campo data de nascimento é obrigatório.',
            'dataNascimento.date' => 'O campo data de nascimento deve ser uma data válida.',
            'dataAdmissao.required' => 'O campo data de admissão é obrigatório.',
            'dataAdmissao.date' => 'O campo data de admissão deve ser uma data válida.',
            'funcao.required' => 'O campo função é obrigatório.',
            'cargo.required' => 'O campo cargo é obrigatório.',
            
            'horarios.*.entrada1.before_or_equal' => 'A entrada não pode ser maior que a saída correspondente.',
            'horarios.*.saida1.after_or_equal' => 'A saída não pode ser menor que a entrada correspondente.',
            'horarios.*.saida1.after' => 'Deve haver um intervalo mínimo de 1 hora entre a primeira saída e a segunda entrada.',
            'horarios.*.entrada1.after_or_equal' => 'Deve haver um intervalo mínimo de 11 horas desde a segunda saída do dia anterior.',
            'horarios.required' => 'O total de horas semanais não pode ser mais que 44 horas.',
            'horarios.*.entrada2.required_with' => 'A segunda entrada é obrigatória se a segunda saída estiver presente.',
        ]);
    }

    private function gerarUsuarioUnico($primeiroNome, $ultimoNome)
    {
        $usuarioBase = Str::snake($primeiroNome . '_' . $ultimoNome);

        $usuario = $usuarioBase;
        $contagem = 1;

        // Verifica se o usuário já existe no banco de dados
        while (User::where('usuario', $usuario)->exists()) {
            $usuario = $usuarioBase . $contagem;
            $contagem++;
        }

        return $usuario;
    }

    private function calcularTotalHorasSemanais($horarios)
    {
        $totalHoras = 0;

        foreach ($horarios as $dia => $horario) {
            // Implemente a lógica para calcular as horas trabalhadas por dia
            // Exemplo básico: calculando a diferença entre entrada e saída
            $entrada1 = isset($horario['entrada1']) ? strtotime($horario['entrada1']) : 0;
            $saida1 = isset($horario['saida1']) ? strtotime($horario['saida1']) : 0;
            $entrada2 = isset($horario['entrada2']) ? strtotime($horario['entrada2']) : 0;
            $saida2 = isset($horario['saida2']) ? strtotime($horario['saida2']) : 0;

            // Calcula o total de horas para este dia
            $totalHorasDia = ($saida1 - $entrada1) + ($saida2 - $entrada2);

            // Converte para horas
            $totalHoras += $totalHorasDia / 3600; // Divide por 3600 para obter horas

            // Implemente outras regras de cálculo conforme necessário
        }

        // Retorna o total de horas semanais
        return $totalHoras;
    }

    public function destroy($matricula)
    {
        $user = User::where('matricula', $matricula)->first();

        if ($user) {
            $user->delete();
        }

        return redirect()->route('colaboradores.view');
    }
}
