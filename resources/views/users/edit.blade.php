<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Editar Usuário</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Usuário</h1>
        <form id="userForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="userId" value="{{ $user->id }}">
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
            </div>
            <div class="form-group">
                <label for="date_of_birth">Data de Nascimento</label>
                <input type="text" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') }}">
            </div>
            <div class="form-group">
                <label for="created_at">Data de Cadastro</label>
                <p class="form-control-static">{{ $user->created_at->format('d/m/Y H:i:s') }}</p>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="form-text text-muted">Deixe em branco para manter a senha atual.</small>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Aplicar máscara de data
            $('#date_of_birth').mask('00/00/0000', { placeholder: '__/__/____' });

            // Configurar o CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Validação do formulário
            $('#userForm').validate({
                rules: {
                    name: "required",
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        minlength: 8
                    },
                    date_of_birth: {
                        date: false // Permite que o campo contenha uma data válida no formato DD/MM/YYYY
                    }
                },
                messages: {
                    name: "Por favor, insira seu nome",
                    email: "Por favor, insira um endereço de e-mail válido",
                    password: {
                        minlength: "Sua senha deve ter pelo menos 8 caracteres"
                    },
                    date_of_birth: "Por favor, insira uma data válida no formato DD/MM/YYYY"
                },
                submitHandler: function(form) {
                    var formData = $(form).serialize();
                    var userId = $('#userId').val();

                    console.log('Enviando dados:', formData); // Log de dados do formulário

                    $.ajax({
                        url: '/users/' + userId, // URL para a rota de atualização
                        type: 'PUT',
                        data: formData,
                        success: function(response) {
                            console.log('Resposta do servidor:', response); // Log da resposta do servidor
                            alert(response.message); // Exibir mensagem de sucesso
                            window.location.href = '{{ route('users.index') }}'; // Redirecionar para a lista de usuários
                        },
                        error: function(xhr) {
                            console.log('Erro:', xhr); // Log de erro
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                alert(value[0]); // Mostrar a primeira mensagem de erro
                            });
                        }
                    });

                    return false; // Impedir o envio padrão do formulário
                }
            });
        });
    </script>
</body>
</html>
