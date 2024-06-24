@extends('layoutPadrao')
@section('title', 'Colaboradores')

@section('conteudo')
<link rel="stylesheet" href="{{ asset('css/colaboradores.css') }}">

<header>
        <h1>Colaboradores</h1>
        <a href="{{ route('novo.colaborador') }}" class="btn btn-primary new-button">Novo Colaborador</a>
    </header>

    <table>
        <thead>
            <tr>
                <th>Matrícula</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->matricula }}</td>
                    <td>{{ $user->nome }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->telefone }}</td>
                    <td>
                        <div class="button-group">
                            <a href="{{ route('editar.colaborador', ['matricula' => $user->matricula]) }}" class="edit-button">&#9998;</a>
                            <form action="{{ route('colaborador.destroy', ['matricula' => $user->matricula]) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-button" onclick="confirmDeletion(event)">&#128465;</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        function confirmDeletion(event) {
            event.preventDefault();
            if (confirm('Tem certeza que deseja excluir este colaborador?')) {
                event.target.closest('form').submit();
            }
        }
    </script>
@endsection

