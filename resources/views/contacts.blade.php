<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CP | Gestión de Clientes</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/15fbf0e0d4.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: 'figtree', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
            margin-left: 200px;
            /* Ajuste para el panel lateral */
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .client-list {
            margin-top: 20px;
        }

        .client-item {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .client-item .client-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }

        .client-item .client-company {
            margin-bottom: 10px;
        }

        .client-item .client-email {
            color: #6c757d;
            margin-bottom: 10px;
        }

        .client-item .client-phone {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0;
        }

        .client-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .client-buttons button {
            margin-left: 5px;
        }
    </style>
</head>

<body>
    @include('header')

    <div class="container">
        <h1 class="mt-4 mb-4">Clientes</h1>

        <div class="client-list">
            <!-- Botón para abrir el modal de creación de cliente -->
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createClientModal">
                Crear Nuevo Cliente
            </button>
            <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#uploadExcelModal">
                Subir Clientes con Excel
            </button>
            @foreach ($contacts as $client)
            <div class="client-item">
                <div class="client-buttons">
                    <button class="btn btn-sm btn-primary edit-client" data-client-id="{{ $client['id'] }}">Editar</button>
                    <button class="btn btn-sm btn-danger delete-client" data-client-id="{{ $client['id'] }}">Eliminar</button>
                </div>
                <div class="client-name">{{ $client['name'] }}</div>
                <div class="client-company">{{ $client['company'] }}</div>
                <div class="client-email">Email: {{ $client['email'] }}</div>
                <div class="client-phone">Teléfono: {{ $client['phone'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para la creación de nuevo cliente -->
    <div class="modal fade" id="createClientModal" tabindex="-1" role="dialog" aria-labelledby="createClientModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClientModalLabel">Crear Nuevo Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createClientForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="clientName">Nombre del Cliente</label>
                            <input type="text" class="form-control" id="clientName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="clientCompany">Empresa</label>
                            <input type="text" class="form-control" id="clientCompany" name="company">
                        </div>
                        <div class="form-group">
                            <label for="clientEmail">Correo Electrónico</label>
                            <input type="email" class="form-control" id="clientEmail" name="email">
                        </div>
                        <div class="form-group">
                            <label for="clientPhone">Teléfono</label>
                            <input type="text" class="form-control" id="clientPhone" name="phone">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para la carga de clientes en masa -->
    <div class="modal fade" id="uploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadExcelModalLabel">Subir Clientes con Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="uploadExcelForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="excelFile">Archivo Excel</label>
                            <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xlsx, .xls, .csv" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Subir Clientes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript para manejar la creación de clientes -->
    <script>
        // Manejar envío de formulario de creación de factura
        document.addEventListener('DOMContentLoaded', function() {
            const createClientForm = document.getElementById('createClientForm');

            createClientForm.addEventListener('submit', function(event) {
                event.preventDefault();

                // Obtener datos del formulario
                const formData = new FormData(createClientForm);
                formData.append('user_id', <?php echo $user_id ?>);

                // Enviar solicitud AJAX para crear la factura
                fetch('/client', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(() => {
                        $('#createClientModal').modal('hide');
                        window.location.reload();
                    })
                    .catch(error => console.error('Error al enviar la solicitud:', error));
            });

            const uploadExcelForm = document.getElementById('uploadExcelForm');

            uploadExcelForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(uploadExcelForm);
                formData.append('user_id', <?php echo $user_id ?>);

                fetch('/client/uploadExcel', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(() => {
                        $('#uploadExcelModal').modal('hide');
                        // window.location.reload();
                    })
                    .catch(error => console.error('Error al enviar la solicitud:', error));
            });
        });
    </script>

    <!-- JavaScript para editar y eliminar proyectos -->
    <script>
        // Escuchar eventos de clic en botones de editar y eliminar
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-client');
            const deleteButtons = document.querySelectorAll('.delete-client');

            // Manejar clic en botón de editar
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const projectId = button.getAttribute('data-client-id');
                    // Redirigir a la página de edición o ejecutar la acción necesaria
                    alert(`Editar proyecto ${projectId}`);
                });
            });

            // Manejar clic en botón de eliminar
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const projectId = button.getAttribute('data-client-id');
                    // Confirmar eliminación
                    if (confirm(`¿Estás seguro de eliminar el proyecto ${projectId}?`)) {
                        // Aquí puedes realizar una petición AJAX para eliminar el proyecto
                        alert(`Eliminar proyecto ${projectId}`);
                    }
                });
            });
        });
    </script>
</body>

</html>