<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Criar Usuário</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Criar Usuário</h2>
        <form id="userForm">
            <div class="form-group">
                <label for="name">Nome:</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="date_of_birth">Data de Nascimento:</label>
                <input type="text" class="form-control" id="date_of_birth" name="date_of_birth" placeholder="__/__/____">
            </div>
            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirmar Senha:</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="#" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script>
      $(document).ready(function() {
        // Aplicar máscara de data
        $('#date_of_birth').mask('00/00/0000', { placeholder: '__/__/____' });

        // Método de validação personalizado para o formato da data
        $.validator.addMethod('dateFormat', function(value, element) {
            return this.optional(element) || /^\d{2}\/\d{2}\/\d{4}$/.test(value);
        }, 'Por favor, insira uma data válida no formato DD/MM/YYYY');

        // Configurar o token CSRF para solicitações AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Inicializar a validação do formulário
        $('#userForm').validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                date_of_birth: {
                    dateFormat: true
                },
                password: {
                    required: true,
                    minlength: 8
                },
                password_confirmation: {
                    equalTo: "#password"
                }
            },
            messages: {
                name: "Por favor, insira seu nome",
                email: "Por favor, insira um endereço de email válido",
                date_of_birth: "Por favor, insira uma data válida no formato DD/MM/YYYY",
                password: {
                    required: "Por favor, forneça uma senha",
                    minlength: "Sua senha deve ter pelo menos 8 caracteres"
                },
                password_confirmation: {
                    equalTo: "A confirmação da senha não corresponde"
                }
            },
            submitHandler: function(form) {
                // Serializar os dados do formulário
                var formData = $(form).serialize();

                // Enviar dados via AJAX
                $.ajax({
                    url: '/users', // URL para sua rota Laravel
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert(response.message); // Exibir mensagem de sucesso
                        // Redirecionar para a página de listagem de usuários
                        window.location.href = '/users';
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        // Exibir mensagens de erro
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
