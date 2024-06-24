<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CP | Gestión de Proyectos</title>

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

        .project-list {
            margin-top: 20px;
        }

        .project-item {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .project-item .project-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }

        .project-item .project-description {
            margin-bottom: 10px;
        }

        .project-item .project-dates {
            color: #6c757d;
            margin-bottom: 10px;
        }

        .project-item .project-budget {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0;
        }

        .project-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .project-buttons button {
            margin-left: 5px;
        }
    </style>
</head>

<body>
    @include('header')

    <div class="container">
        <h1 class="mt-4 mb-4">Gestión de Proyectos</h1>

        <div class="project-list">
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createProjectModal">
                Crear Nuevo Proyecto
            </button>
            @foreach ($proyectos as $project)
            <div class="project-item">
                <div class="project-buttons">
                    <button class="btn btn-sm btn-primary edit-project" data-project-id="{{ $project['id'] }}">Editar</button>
                    <button class="btn btn-sm btn-danger delete-project" data-project-id="{{ $project['id'] }}">Eliminar</button>
                </div>
                <div class="project-name">{{ $project['nombre'] }}</div>
                <div class="project-description">{{ $project['descripcion'] }}</div>
                <div class="project-dates">Fecha de inicio: {{ $project['fecha_inicio'] }} - Fecha de fin: {{ $project['fecha_fin'] }}</div>
                <div class="project-budget">Presupuesto: ${{ number_format($project['presupuesto'], 2, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para la creación de nuevo proyecto -->
    <div class="modal fade" id="createProjectModal" tabindex="-1" role="dialog" aria-labelledby="createProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProjectModalLabel">Crear Nuevo Proyecto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createProjectForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="projectName">Nombre del Proyecto</label>
                            <input type="text" class="form-control" id="projectName" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="projectDescription">Descripción</label>
                            <textarea class="form-control" id="projectDescription" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="projectStartDate">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="projectStartDate" name="fecha_inicio">
                        </div>
                        <div class="form-group">
                            <label for="projectEndDate">Fecha de Fin</label>
                            <input type="date" class="form-control" id="projectEndDate" name="fecha_fin">
                        </div>
                        <div class="form-group">
                            <label for="projectBudget">Presupuesto</label>
                            <input type="number" class="form-control" id="projectBudget" name="presupuesto" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Proyecto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript para manejar la creación de proyectos -->
    <script>
        // Manejar envío de formulario de creación de factura
        document.addEventListener('DOMContentLoaded', function() {
            const createProjectForm = document.getElementById('createProjectForm');

            createProjectForm.addEventListener('submit', function(event) {
                event.preventDefault();

                // Obtener datos del formulario
                const formData = new FormData(createProjectForm);

                // Enviar solicitud AJAX para crear la factura
                fetch('/proyecto', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(() => {
                        $('#createInvoiceModal').modal('hide');
                        window.location.reload();
                    })
                    .catch(error => console.error('Error al enviar la solicitud:', error));
            });
        });
    </script>

    <!-- JavaScript para editar y eliminar proyectos -->
    <script>
        // Escuchar eventos de clic en botones de editar y eliminar
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-project');
            const deleteButtons = document.querySelectorAll('.delete-project');

            // Manejar clic en botón de editar
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const projectId = button.getAttribute('data-project-id');
                    // Redirigir a la página de edición o ejecutar la acción necesaria
                    alert(`Editar proyecto ${projectId}`);
                });
            });

            // Manejar clic en botón de eliminar
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const projectId = button.getAttribute('data-project-id');
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