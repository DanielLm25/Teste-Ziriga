<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>User List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 20px;
        }
        .container {
            max-width: 1200px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User List</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Create New User</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date of Creation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                        <button class="btn btn-danger delete-user" data-id="{{ $user->id }}">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.delete-user').click(function() {
                if (confirm('Você tem certeza que deseja excluir esse usuário?')) {
                    let userId = $(this).data('id');
                    console.log('Deleting user with ID:', userId);

                    $.ajax({
                        url: '/users/' + userId,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            console.log('Delete success:', response);
                            alert(response.message);
                            location.reload();
                        },
                        error: function(xhr) {
                            console.log('Delete error:', xhr.responseText);
                            alert('An error occurred: ' + xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
    