<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CP | Gestión de Servicios</title>

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
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .service-list {
            margin-top: 20px;
        }

        .service-item {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .service-item .service-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }

        .service-item .service-description {
            margin-bottom: 10px;
        }

        .service-item .service-price {
            color: #6c757d;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .service-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .service-buttons button {
            margin-left: 5px;
        }
    </style>
</head>

<body>
    @include('header')

    <div class="container">
        <h1 class="mt-4 mb-4">Servicios</h1>

        <div class="service-list">
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createServiceModal">
                Crear Nuevo Servicio
            </button>
            @foreach ($services as $service)
            <div class="service-item">
                <div class="service-buttons">
                    <button class="btn btn-sm btn-primary edit-service" data-service-id="{{ $service->id }}">Editar</button>
                    <button class="btn btn-sm btn-danger delete-service" data-service-id="{{ $service->id }}">Eliminar</button>
                </div>
                <div class="service-name">{{ $service->name }}</div>
                <div class="service-description">{{ $service->description }}</div>
                <div class="service-price">Precio: ${{ number_format($service->price, 2) }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para la creación de nuevo servicio -->
    <div class="modal fade" id="createServiceModal" tabindex="-1" role="dialog" aria-labelledby="createServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createServiceModalLabel">Crear Nuevo Servicio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createServiceForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="serviceName">Nombre del Servicio</label>
                            <input type="text" class="form-control" id="serviceName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="serviceDescription">Descripción</label>
                            <textarea class="form-control" id="serviceDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="servicePrice">Precio</label>
                            <input type="number" class="form-control" id="servicePrice" name="price" step="0.01" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Servicio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript para manejar la creación de servicios -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const createServiceForm = document.getElementById('createServiceForm');
            createServiceForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(createServiceForm);
                fetch('/service', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(() => {
                        $('#createServiceModal').modal('hide');
                        window.location.reload();
                    })
                    .catch(error => console.error('Error al enviar la solicitud:', error));
            });
        });
    </script>

    <!-- JavaScript para editar y eliminar servicios -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-service');
            const deleteButtons = document.querySelectorAll('.delete-service');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const serviceId = button.getAttribute('data-service-id');
                    alert(`Editar servicio ${serviceId}`);
                });
            });
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const serviceId = button.getAttribute('data-service-id');
                    if (confirm(`¿Estás seguro de eliminar el servicio ${serviceId}?`)) {
                        alert(`Eliminar servicio ${serviceId}`);
                    }
                });
            });
        });
    </script>
</body>

</html>