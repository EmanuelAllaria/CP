<?php

use App\Models\Proyecto;

$proyectos = Proyecto::all();
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="scrollbar-width: none;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CP | Gestión de Tareas</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/15fbf0e0d4.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: 'inter', sans-serif;
            background-color: #11121E;
            padding: 20px;
            margin-left: 200px;
        }

        .task-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: 1fr;
            grid-column-gap: 5px;
            grid-row-gap: 5px;
        }

        .task-item {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .task-item .task-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }

        .task-item .task-description {
            margin-bottom: 10px;
        }

        .task-item .task-due-date {
            color: #6c757d;
            margin-bottom: 10px;
        }

        .task-item .task-status {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0;
        }

        .task-item.completed {
            background-color: #f0f8ff;
        }

        .task-item.completed .task-name,
        .task-item.completed .task-description,
        .task-item.completed .task-due-date,
        .task-item.completed .task-status {
            text-decoration: line-through;
        }

        .task-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .task-buttons button {
            margin-left: 5px;
        }
    </style>
</head>

<body>
    @include('header')

    <div class="container">
        <h1 class="mt-4 mb-4 text-white">Tareas</h1>

        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createTaskModal">
            Crear Nueva Tarea
        </button>
        <div class="task-list">
            @foreach ($tareas as $task)
            <div class="task-item {{ $task->completada ? 'completed' : '' }}">
                <div class="task-buttons">
                    <button class="btn btn-sm btn-primary edit-task" data-task-id="{{ $task->proyecto_id }}" {{ $task->completada ? 'disabled' : '' }}>Editar</button>
                    <button class="btn btn-sm btn-danger delete-task" data-task-id="{{ $task->proyecto_id }}" {{ $task->completada ? 'disabled' : '' }}>Eliminar</button>
                    <form id="updateTaskForm-{{ $task->id }}" style="display:inline;">
                        @csrf
                        <input type="hidden" name="completada" value="{{ $task->completada ? 0 : 1 }}">
                        <button type="submit" class="btn btn-sm btn-success">{{ $task->completada ? 'Desmarcar' : 'Completar' }}</button>
                    </form>
                </div>
                <div class="task-name">Proyecto: {{ $task->nombre_proyecto }}</div>
                <div class="task-name">{{ $task->nombre }}</div>
                <div class="task-description">{{ $task->descripcion }}</div>
                @if (isset($task->fecha_limite)) <div class="task-due-date">Fecha límite: {{ $task->fecha_limite }}</div> @endif
                <div class="task-status">
                    @if ($task->completada)
                    Completada
                    @else
                    Pendiente
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para la creación de nueva tarea -->
    <div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTaskModalLabel">Crear Nueva Tarea</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createTaskForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="taskProyecto">Proyecto</label>
                            <select name="proyecto_id" class="form-control" id="taskProyecto" required>
                                @foreach ($proyectos as $proyecto)
                                <option value="{{ $proyecto->id }}">{{ $proyecto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="taskName">Nombre de la Tarea</label>
                            <input type="text" class="form-control" id="taskName" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="taskDescription">Descripción</label>
                            <textarea class="form-control" id="taskDescription" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="taskDueDate">Fecha Límite</label>
                            <input type="date" class="form-control" id="taskDueDate" name="fecha_limite">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Tarea</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const createTaskForm = document.getElementById('createTaskForm');
            createTaskForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(createTaskForm);
                formData.append('completada', 0);
                formData.append('user_id', <?php echo $user_id ?>);
                fetch('/tarea', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(() => {
                        $('#createTaskModal').modal('hide');
                        window.location.reload();
                    })
                    .catch(error => console.error('Error al enviar la solicitud:', error));
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const updateTaskForms = document.querySelectorAll('form[id^="updateTaskForm-"]');
            updateTaskForms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const taskId = form.id.split('-')[1];
                    const formData = new FormData(form);
                    fetch(`/tarea/${taskId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(() => window.location.reload())
                        .catch(error => console.error('Error al enviar la solicitud:', error));
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-task');
            const deleteButtons = document.querySelectorAll('.delete-task');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const taskId = button.getAttribute('data-task-id');
                    alert(`Editar tarea ${taskId}`);
                });
            });
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const taskId = button.getAttribute('data-task-id');
                    if (confirm(`¿Estás seguro de eliminar la tarea ${taskId}?`)) {
                        alert(`Eliminar tarea ${taskId}`);
                    }
                });
            });
        });
    </script>
</body>

</html>