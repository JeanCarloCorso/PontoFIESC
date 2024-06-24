@extends('layoutPadrao')
@section('title', 'Colaboradores')

@section('conteudo')

<link rel="stylesheet" href="{{ asset('css/registroPonto.css') }}">

<div class="header">
            <h1 class="date">22/06/2024</h1>
            <h3 class="time">12:03</h3>
        </div>
        <div class="info">
            <span><strong>Colaborador:</strong> {{auth()->user()->nome}}</span>
            <span><strong>Matrícula:</strong> {{auth()->user()->matricula}}</span>
        </div>
        <div class="records">
            <div class="entries">
                <strong>Entradas</strong>
                @foreach ($registros as $registro)
                    @if ($registro->tipo == 'entrada')
                        <div class="entry-time">{{ $registro->hora }}</div>
                    @endif
                @endforeach
            </div>
            <div class="exits">
                <strong>Saídas</strong>
                @foreach ($registros as $registro)
                    @if ($registro->tipo == 'saida')
                        <div class="exit-time">{{ $registro->hora}}</div>
                    @endif
                @endforeach
            </div>
        </div>
        <a href="{{ route('bate.ponto') }}" class="btn btn-primary">Registrar</a>

        <script src="js/registroPonto.js"></script>
@endsection