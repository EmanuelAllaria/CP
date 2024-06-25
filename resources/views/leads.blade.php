<?php

use App\Models\Contact;
use App\Models\Producto;

$productos = Producto::all();
$clientes = Contact::all();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Leads</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos adicionales -->
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

        .lead-card {
            background-color: #fff;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .lead-card .card-body {
            padding: 20px;
        }

        .lead-card .card-title {
            font-size: 1.5rem;
            color: #007bff;
        }

        .lead-card .card-text {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    @include('header')

    <div class="container">
        <h1>Lista de Leads</h1>
        <!-- Botón para abrir el modal de creación de lead -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createLeadModal">
            Crear Nuevo Lead
        </button>
        @foreach ($leads as $lead)
        <div class="card lead-card">
            <div class="card-body">
                <h5 class="card-title">{{ $lead->nombre }}</h5>
                <p class="card-text">Email: {{ $lead->email }}</p>
                <p class="card-text">Teléfono: {{ $lead->telefono }}</p>
                <p class="card-text">Monto: ${{ number_format($lead->monto, 2, ',', '.') }}</p>
                <p class="card-text">Estado: {{ $lead->estado }}</p>
                @if ($lead->estado !== 'convertido')
                <form action="{{ route('leads.convertir', $lead) }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-primary">Convertir a Cliente</button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Modal para la creación de nuevo cliente -->
    <div class="modal fade" id="createLeadModal" tabindex="-1" role="dialog" aria-labelledby="createLeadModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLeadModalLabel">Crear Nuevo Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createLeadForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="clientExistente">Es un cliente existente?</label>
                            <select class="form-control" name="clientExistente" id="clientExistente" required>
                                <option value="si">Si</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="form-group cliente-no-existente">
                            <label for="clientName">Nombre del Cliente</label>
                            <input type="text" class="form-control" id="clientName" name="nombre">
                        </div>
                        <div class="form-group cliente-no-existente">
                            <label for="clientEmail">Correo Electrónico</label>
                            <input type="email" class="form-control" id="clientEmail" name="email">
                        </div>
                        <div class="form-group cliente-no-existente">
                            <label for="clientPhone">Teléfono</label>
                            <input type="number" class="form-control" id="clientPhone" name="telefono">
                        </div>
                        <div class="form-group cliente-existente">
                            <label for="clienteId">Elige el cliente</label>
                            <select class="form-control" name="cliente_id" id="clienteId">
                                @foreach ($clientes as $client)
                                <option value="{{ $client->id }}">{{ $client->name }} - {{ $client->company }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="productoId">Elige el producto</label>
                            <select class="form-control" name="producto_id" id="productoId" required>
                                @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}" price="{{ $producto->price }}">{{ $producto->name }} - {{ $producto->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Lead</button>
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
            var clienteExistente = $('#clientExistente');
            var clienteExistenteInputs = $('.cliente-existente');
            var clienteNoExistenteSelect = $('.cliente-no-existente');

            // Initial check on page load
            if (clienteExistente.val() == 'si') {
                clienteExistenteInputs.show();
                clienteNoExistenteSelect.hide();
            } else {
                clienteNoExistenteSelect.show();
                clienteExistenteInputs.hide();
            }

            // Change event listener for clienteExistente
            clienteExistente.on('change', function() {
                if (clienteExistente.val() == 'si') {
                    clienteExistenteInputs.show();
                    clienteNoExistenteSelect.hide();
                } else {
                    clienteNoExistenteSelect.show();
                    clienteExistenteInputs.hide();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const createLeadForm = document.getElementById('createLeadForm');

            createLeadForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData();
                formData.append('nombre', $('#clientName').val());
                formData.append('email', $('#clientEmail').val());
                formData.append('telefono', $('#clientPhone').val());
                formData.append('cliente_id', $('#clientExistente').val() === 'si' ? $('#clienteId').val() : null);
                formData.append('producto_id', $('#productoId').val());
                formData.append('cantidad', $('#cantidad').val());
                formData.append('monto', parseFloat($('#productoId option:selected').attr('price')) * parseInt($('#cantidad').val()));

                // Send AJAX request to create invoice
                fetch('/lead', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        $('#createLeadModal').modal('hide');
                        window.location.reload();
                    })
                    .catch(error => console.error('Error al enviar la solicitud:', error));
            });
        });
    </script>
</body>

</html>