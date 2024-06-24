<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ponto</title>
    <link rel="icon" type="image/x-icon" href={{ asset('Marca-FIESC.ico') }}>
    <link rel="stylesheet" href={{ asset('css/login.css') }}>
</head>
<body>
    <div class="container">
        <div class="image-container">
            <img src={{asset('img/tela_login.png')}} alt="Imagem tela login">
        </div>
        <div class="login-container">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h2>Acesse sua conta</h2>
                <div class="input-group">
                    <label for="usuario">Login*</label>
                    <input type="text" id="usuario" name="usuario" required autofocus>
                </div>
                <div class="input-group">
                    <label for="senha">Senha*</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <div class="forgot-password">
                    <a href="#">Forgot Password?</a>
                </div>
                <button type="submit">ENTRAR</button>

                @if ($errors->any())
                    <div>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>
    </div>
</body>
</html>
