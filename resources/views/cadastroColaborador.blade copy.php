@extends('layoutPadrao')
@section('title', 'Colaboradores')

@section('conteudo')

<style>
    .notification-container {
        position: fixed;
        width: 320px; /* Largura da área de notificação */
        padding: 10px;
        z-index: 9999;
    }
    .error-notification {
        display: flex;
        align-items: center; /* Alinhamento vertical central */
        justify-content: center; /* Alinhamento horizontal central */
        height: 60px; /* Altura da notificação */
        font-size: 15px; /* Tamanho da fonte */
        margin-bottom: 10px;
        background-color: #ff6347; /* Cor de fundo para representar um erro */
        color: white;
        border-radius: 5px;
        animation: fadeOut 5s forwards; /* Define a animação de desaparecimento após 10 segundos */
    }
    @keyframes fadeOut {
        0% { opacity: 1; }
        90% { opacity: 1; }
        100% { opacity: 0; display: none; }
    }
    .top-right {
        top: 60px; /* Ajuste a posição vertical conforme desejado */
        right: 20px; /* Ajuste a posição horizontal conforme desejado */
    }
</style>

<link rel="stylesheet" href="{{ asset('css/cadastroColaborador.css') }}">
    @if($criacao)
        <h1>Inserir Colaborador</h1>
    @else
        <h1>Alterar Colaborador</h1>
    @endif

    <form id="formularioColaborador" class="form" action="{{ route('colaboradores.cadastrar') }}" method="POST">
        @csrf
        <div class="form-row">
            <div @if($criacao) hidden @endif class="form-group">
                <label for="matricula">Matrícula</label>
                <input type="text" id="matricula" class="form-control" value="001" disabled="">
            </div>
            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf" class="form-control" @if(!$criacao) disabled @endif required placeholder="xxx.xxx.xxx-xx">
            </div>
            <div @if($criacao) hidden @endif class="form-group">
                <label for="usuario">Usuário</label>
                <input type="text" name="usuario" id="usuario" class="form-control" disabled="">
            </div>
            <div class="form-group checkbox-group">
                <input type="checkbox" name="ativo" id="ativo" disabled>
                <label for="ativo">Ativo?</label>
            </div>
        </div>
        
        <!-- Adicionar id ao container de outros campos para controle do JavaScript -->
        <div id="outros-campos">
            <div class="form-row">
                <div class="form-group">
                    <label for="nome">Nome*</label>
                    <input type="text" name="nome" id="nome" class="form-control" required>
                    <div id="nome-feedback" class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="data_nascimento">Data de Nascimento*</label>
                    <input type="date" name="dataNascimento" id="data_nascimento" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="data_admissao">Data de Admissão*</label>
                    <input type="date" name="dataAdmissao" id="data_admissao" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="telefone">Telefone*</label>
                    <input type="text" name="telefone" id="telefone" class="form-control" required placeholder="(xx) xxxxx-xxxx" pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}">
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo*</label>
                    <select name="tipo" id="tipo" class="form-control" required>
                        <option value="usuario">Usuário</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="email">E-mail*</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                    <div id="email-feedback" class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="cargo">Cargo*</label>
                    <select id="cargo" name="cargo" class="form-control" required>
                        <option value="">Selecione um cargo</option>
                        @foreach($cargos as $cargo)
                            <option value="{{ $cargo->id }}">{{ $cargo->descricao }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="funcao">Função*</label>
                    <select id="funcao" name="funcao" class="form-control" required>
                        <option value="">Selecione uma função</option>
                        @foreach($funcoes as $funcao)
                            <option value="{{ $funcao->id }}">{{ $funcao->descricao }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="data_rescisao">Data de Rescisão</label>
                    <input type="date" name="dataRecisao" id="data_rescisao" class="form-control">
                    <div id="data_rescisao_feedback" class="invalid-feedback"></div>
                </div>
                <div @if(!$criacao) hidden @endif class="form-group">
                    <label for="usuario">Usuário</label>
                    <label id="usuario" class="form-control disabled-label"></label>
                </div>
            </div>
            <fieldset class="form-fieldset">
                <legend>Horários</legend>
                <table class="horarios-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Segunda-feira</th>
                            <th>Terça-feira</th>
                            <th>Quarta-feira</th>
                            <th>Quinta-feira</th>
                            <th>Sexta-feira</th>
                            <th>Sábado</th>
                            <th>Domingo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Entrada</td>
                            <td><input type="time" name="horarios[0][entrada1]" class="form-control"></td>
                            <td><input type="time" name="horarios[1][entrada1]" class="form-control"></td>
                            <td><input type="time" name="horarios[2][entrada1]" class="form-control"></td>
                            <td><input type="time" name="horarios[3][entrada1]" class="form-control"></td>
                            <td><input type="time" name="horarios[4][entrada1]" class="form-control"></td>
                            <td><input type="time" name="horarios[5][entrada1]" class="form-control"></td>
                            <td><input type="time" name="horarios[6][entrada1]" class="form-control"></td>
                        </tr>
                        <tr>
                            <td>Saída</td>
                            <td><input type="time" name="horarios[0][saida1]" class="form-control"></td>
                            <td><input type="time" name="horarios[1][saida1]" class="form-control"></td>
                            <td><input type="time" name="horarios[2][saida1]" class="form-control"></td>
                            <td><input type="time" name="horarios[3][saida1]" class="form-control"></td>
                            <td><input type="time" name="horarios[4][saida1]" class="form-control"></td>
                            <td><input type="time" name="horarios[5][saida1]" class="form-control"></td>
                            <td><input type="time" name="horarios[6][saida1]" class="form-control"></td>
                        </tr>
                        <tr>
                            <td>Entrada</td>
                            <td><input type="time" name="horarios[0][entrada2]" class="form-control"></td>
                            <td><input type="time" name="horarios[1][entrada2]" class="form-control"></td>
                            <td><input type="time" name="horarios[2][entrada2]" class="form-control"></td>
                            <td><input type="time" name="horarios[3][entrada2]" class="form-control"></td>
                            <td><input type="time" name="horarios[4][entrada2]" class="form-control"></td>
                            <td><input type="time" name="horarios[5][entrada2]" class="form-control"></td>
                            <td><input type="time" name="horarios[6][entrada2]" class="form-control"></td>
                        </tr>
                        <tr>
                            <td>Saída</td>
                            <td><input type="time" name="horarios[0][saida2]" class="form-control"></td>
                            <td><input type="time" name="horarios[1][saida2]" class="form-control"></td>
                            <td><input type="time" name="horarios[2][saida2]" class="form-control"></td>
                            <td><input type="time" name="horarios[3][saida2]" class="form-control"></td>
                            <td><input type="time" name="horarios[4][saida2]" class="form-control"></td>
                            <td><input type="time" name="horarios[5][saida2]" class="form-control"></td>
                            <td><input type="time" name="horarios[6][saida2]" class="form-control"></td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
            
            <button id="botao-salvar" type="submit" class="btn">Salvar</button>
        </div> 
    </form>

    <div class="notification-container top-right">
        @if ($errors->any())
            <div class="error-notification larger">
                @php $i = 0; @endphp
                @foreach($errors->all() as $error)
                    @if($i % 2 != 0)
                        <div class="error-notification larger">{{ $error }}</div>
                    @endif
                    @php $i++; @endphp
                @endforeach
            </div>
        @endif
    </div>
    <!-- Adiciona jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Adiciona o JavaScript para verificação do CPF e bloqueio dos campos -->
    <script>
    $(document).ready(function() {
        $('#cpf').on('input', function() {
            var cpf = $(this).val().replace(/\D/g, '');
            if (cpf.length === 11) { 
                $.ajax({
                    url: "{{ route('verificar.cpf') }}",
                    type: 'GET',
                    data: { cpf: cpf },
                    success: function(response) {
                        if (response.exists) {
                            $('#outros-campos :input, #outros-campos button').prop('disabled', true);
                            alert('CPF já cadastrado. Os demais campos foram bloqueados.');
                        } else {
                            $('#outros-campos :input, #outros-campos button').prop('disabled', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro na verificação do CPF:', error);
                    }
                });
            } else {
                $('#outros-campos :input').prop('disabled', false); // Habilita os campos se o CPF não tem 11 dígitos
            }
        });

        // Formatação e validação de caracteres do campo CPF
        $('#cpf').on('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove caracteres não numéricos
            if (value.length > 11) {
                value = value.slice(0, 11);
            }

            if (value.length > 9) {
                value = value.slice(0, 9) + '-' + value.slice(9);
            }
            if (value.length > 6) {
                value = value.slice(0, 6) + '.' + value.slice(6);
            }
            if (value.length > 3) {
                value = value.slice(0, 3) + '.' + value.slice(3);
            }

            e.target.value = value;
        });

        $('#cpf').on('keydown', function(e) {
            const allowedKeys = [8, 37, 38, 39, 40];
            if (!allowedKeys.includes(e.keyCode) && (e.keyCode < 48 || e.keyCode > 57)) {
                e.preventDefault();
            }
        });

        $('#telefone').on('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');

            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            if (value.length > 10) {
                value = '(' + value.slice(0, 2) + ') ' + value.slice(2, 7) + '-' + value.slice(7);
            } else if (value.length > 6) {
                value = '(' + value.slice(0, 2) + ') ' + value.slice(2, 6) + '-' + value.slice(6);
            } else if (value.length > 2) {
                value = '(' + value.slice(0, 2) + ') ' + value.slice(2);
            }

            e.target.value = value;
        });

        $('#telefone').on('keydown', function(e) {
            const allowedKeys = [8, 37, 38, 39, 40];
            if (!allowedKeys.includes(e.keyCode) && (e.keyCode < 48 || e.keyCode > 57)) {
                e.preventDefault();
            }
        });

        $('#email').on('input', function() {
            validarEmail($(this).val());
        });

        function validarEmail(email) {
            // Expressão regular para validar o formato do e-mail
            const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (regexEmail.test(email)) {
                $('#email').removeClass('is-invalid').addClass('is-valid');
                $('#outros-campos button').prop('disabled', false);
                $('#email-feedback').text('');
            } else {
                $('#email').removeClass('is-valid').addClass('is-invalid');
                $('#outros-campos button').prop('disabled', true);
                $('#email-feedback').text('Por favor, insira um e-mail no formato "email@dominio.com".');
            }
        }

        $('#nome').on('input', function() {
            validarNome($(this).val());
        });

        function validarNome(nome) {
            const regexNome = /^[A-Z][a-z]+(?: [A-Z][a-z]+)+$/;
            
            if (regexNome.test(nome)) {
                $('#nome').removeClass('is-invalid').addClass('is-valid');
                $('#nome-feedback').text('Formato de nome válido.');
                $('#outros-campos button').prop('disabled', false);
            } else {
                $('#nome').removeClass('is-valid').addClass('is-invalid');
                $('#nome-feedback').text('Por favor, insira um nome no formato "Nome Sobrenome" com as primeiras letras em maiúscula.');
                $('#outros-campos button').prop('disabled', true);
            }
        }

        $('#data_admissao, #data_rescisao').on('change', function() {
            validarDatas();
        });

        function validarDatas() {
            var dataAdmissao = new Date($('#data_admissao').val());
            var dataRescisao = new Date($('#data_rescisao').val());

            // Verifica se a data de rescisão é anterior à data de admissão
            if (dataRescisao < dataAdmissao) {
                $('#data_rescisao').addClass('is-invalid');
                $('#data_rescisao_feedback').text('A data de rescisão não pode ser anterior à data de admissão.');
                $('#outros-campos button').prop('disabled', true);
            } else {
                $('#data_rescisao').removeClass('is-invalid');
                $('#data_rescisao_feedback').text('');
                $('#outros-campos button').prop('disabled', false);
            }
        }

        validarAtivo();

        $('#data_rescisao').on('change', function() {
            validarAtivo($(this).val());
        });

        $('#data_admissao').on('change', function() {
            validarAtivo();
        });

        function validarAtivo(dataRescisao) {
            // Obtém a data atual
            var dataAtual = new Date();
            console.log('Palavra: ');
            // Verifica se a data de rescisão não está preenchida ou se é menor que a data atual
            if (!dataRescisao || new Date(dataRescisao) > dataAtual) {
                $('#ativo').prop('checked', true);
            } else {
                $('#ativo').prop('checked', false);
            }
            $('#ativo').prop('disabled', true);
        }

        function atualizarUsuario() {

            const nome = document.getElementById('nome').value;
            const palavras = nome.split(' ');
            
            console.log('Palavra: ', palavras);
            if (palavras.length > 1) {
                

                const primeiraPalavra = palavras[0];
                const ultimaPalavra = palavras[palavras.length - 1];
                console.log('entrei', primeiraPalavra, ultimaPalavra);
                document.getElementById('usuario').innerText = `${primeiraPalavra}_${ultimaPalavra}`;
            } else {
                document.getElementById('usuario').innerText = 'your tip has been submitted!';
            }
        }

        $('#nome').on('input', function() {
            atualizarUsuario();
        });
    });

</script>

@endsection
