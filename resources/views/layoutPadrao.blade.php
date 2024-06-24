<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/layoutPadrao.css') }}">
    
    <title>@yield('title', 'Ponto FIESC')</title>
</head>
<body>
    <nav>
        <div class="nav-left">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
            <span class="site-name">Ponto FIESC</span>
        </div>
        <div class="nav-right">
                @if(auth()->user()->tipo == "administrador")
                    <a class="user-name" href="{{ route('colaboradores.view') }}">Colaboradores</a></li>
                @endif
                <a class="user-name" href="{{ route('ponto.view') }}">Bater Ponto</a></li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Sair</a>

            <!-- Formulário de logout oculto -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </nav>

    <div class="container">
    <main class="flex-fill">
            @yield('conteudo')
            <br/>
            <br/>
    </main>
    </div>

</body>
</html>
