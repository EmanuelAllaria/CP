<?php

use App\Models\Contact;
use App\Models\Producto;
use App\Models\Service;

$productos = Producto::all();
$servicios = Service::all();
$clients = Contact::all();
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="scrollbar-width: none;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Cotizaciones</title>

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

        .quote-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: 1fr;
            grid-column-gap: 5px;
            grid-row-gap: 5px;
        }

        .quote-item {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .quote-item .quote-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }

        .quote-item .quote-details {
            margin-bottom: 10px;
        }

        .quote-item .quote-amount {
            color: #6c757d;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .quote-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .quote-buttons button {
            margin-left: 5px;
        }
    </style>
</head>

<body>
    @include('header')

    <div class="container">
        <h1 class="mt-4 mb-4 text-white">Cotizaciones</h1>

        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createQuoteModal">
            Crear Nueva Cotización
        </button>
        @if (isset($cotizaciones[0]['id']))
        <div class="quote-list">
            @foreach ($cotizaciones as $quote)
            <div class="quote-item">
                <div class="quote-buttons">
                    <a target="_blank" href="/cotizacion/{{ $quote->id }}"><i class="fas fa-file-invoice"></i></a>
                    <button class="btn btn-sm btn-success edit-quote" data-quote-id="{{ $quote->id }}">Cambiar Estado</button>
                    <button class="btn btn-sm btn-danger delete-quote" data-quote-id="{{ $quote->id }}">Eliminar</button>
                </div>
                <div class="quote-name">Cliente: {{ $quote->client_name }}</div>
                <div class="quote-amount">Monto: ${{ number_format($quote->amount, 2) }}</div>
                <div class="quote-status">Estado: {{ $quote->status }}</div>
                <div class="quote-expiration-date">Fecha de Expiración: {{ $quote->expiration_date }}</div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-white text-center">No hay cotizaciones creadas.</p>
        @endif
    </div>

    <!-- Modal para la creación de nueva cotización -->
    <div class="modal fade" id="createQuoteModal" tabindex="-1" role="dialog" aria-labelledby="createQuoteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createQuoteModalLabel">Crear Nueva Cotización</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createQuoteForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="quoteClientName">Nombre del Cliente</label>
                            <input type="text" class="form-control" id="quoteClientName" name="client_name" required>
                        </div>
                        <div class="form-group">
                            <label for="que_presupuesta">Que presupuesta?</label>
                            <select id="que_presupuesta" class="form-control" required>
                                <option value="servicios">Servicios</option>
                                <option value="productos">Productos</option>
                            </select>
                        </div>
                        <div class="servicios-seleccionados">
                            <div class="form-group">
                                <label for="invoiceService">Servicio</label>
                                <select id="invoiceService" class="form-control">
                                    @foreach ($servicios as $servicio)
                                    <option value="{{ $servicio->id }}" data-price="{{ $servicio->price }}">{{ $servicio->name }} - ${{ $servicio->price }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-secondary mt-2" id="addServiceBtn">Agregar Servicio</button>
                            </div>
                            <div class="form-group">
                                <label for="selectedServices">Servicios Seleccionados</label>
                                <ul id="selectedServices" class="list-group"></ul>
                            </div>
                        </div>
                        <div class="productos-seleccionados">
                            <div class="form-group">
                                <label for="invoiceProduct">Productos</label>
                                <select id="invoiceProduct" class="form-control">
                                    @foreach ($productos as $producto)
                                    <option value="{{ $producto->id }}" data-price="{{ $producto->price }}">{{ $producto->name }} - ${{ $producto->price }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-secondary mt-2" id="addProductBtn">Agregar Producto</button>
                            </div>
                            <div class="form-group">
                                <label for="selectedProducts">Productos Seleccionados</label>
                                <ul id="selectedProducts" class="list-group"></ul>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="invoiceAmount">Monto</label>
                            <input type="number" class="form-control" id="invoiceAmount" name="amount" step="0.01" min="0" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="quoteExpirationDate">Fecha de Expiración</label>
                            <input type="date" class="form-control" id="quoteExpirationDate" name="expiration_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Cotización</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para editar cotización -->
    <div class="modal fade" id="editQuoteModal" tabindex="-1" role="dialog" aria-labelledby="editQuoteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editQuoteModalLabel">Cambiar Estado de la Cotización</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editQuoteForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="editQuoteId" name="quote_id">
                        <div class="form-group">
                            <label for="quoteStatus">Estado</label>
                            <select class="form-control" id="quoteStatus" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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
            var que_presupuesta = $('#que_presupuesta');
            var servicios_seleccionados = $('.servicios-seleccionados');
            var productos_seleccionados = $('.productos-seleccionados');

            // Initial check on page load
            if (que_presupuesta.val() == 'servicios') {
                servicios_seleccionados.show();
                productos_seleccionados.hide();
            } else {
                productos_seleccionados.show();
                servicios_seleccionados.hide();
            }

            // Change event listener for clienteExistente
            que_presupuesta.on('change', function() {
                if (que_presupuesta.val() == 'servicios') {
                    servicios_seleccionados.show();
                    productos_seleccionados.hide();
                } else {
                    productos_seleccionados.show();
                    servicios_seleccionados.hide();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addServiceBtn = document.getElementById('addServiceBtn');
            const selectedServices = document.getElementById('selectedServices');
            const invoiceAmount = document.getElementById('invoiceAmount');

            addServiceBtn.addEventListener('click', function() {
                const serviceSelect = document.getElementById('invoiceService');
                const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                const serviceId = selectedOption.value;
                const serviceName = selectedOption.text;
                const servicePrice = parseFloat(selectedOption.getAttribute('data-price'));

                if (serviceId) {
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                    listItem.dataset.id = serviceId;
                    listItem.dataset.price = servicePrice;
                    listItem.innerHTML = `${serviceName} <button type="button" class="btn btn-danger btn-sm remove-service-btn">&times;</button>`;

                    selectedServices.appendChild(listItem);
                    updateTotal();
                }
            });

            selectedServices.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-service-btn')) {
                    const listItem = event.target.closest('li');
                    listItem.remove();
                    updateTotal();
                }
            });

            function updateTotal() {
                let total = 0;
                const serviceItems = selectedServices.querySelectorAll('li');
                serviceItems.forEach(item => {
                    total += parseFloat(item.dataset.price);
                });
                invoiceAmount.value = total.toFixed(2);
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addProductBtn = document.getElementById('addProductBtn');
            const selectedProducts = document.getElementById('selectedProducts');
            const invoiceAmount = document.getElementById('invoiceAmount');

            addProductBtn.addEventListener('click', function() {
                const productSelect = document.getElementById('invoiceProduct');
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const productId = selectedOption.value;
                const productName = selectedOption.text;
                const productPrice = parseFloat(selectedOption.getAttribute('data-price'));

                if (productId) {
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                    listItem.dataset.id = productId;
                    listItem.dataset.price = productPrice;
                    listItem.innerHTML = `${productName} <button type="button" class="btn btn-danger btn-sm remove-product-btn">&times;</button>`;

                    selectedProducts.appendChild(listItem);
                    updateTotal();
                }
            });

            selectedProducts.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-product-btn')) {
                    const listItem = event.target.closest('li');
                    listItem.remove();
                    updateTotal();
                }
            });

            function updateTotal() {
                let total = 0;
                const productItems = selectedProducts.querySelectorAll('li');
                productItems.forEach(item => {
                    total += parseFloat(item.dataset.price);
                });
                invoiceAmount.value = total.toFixed(2);
            }
        });
    </script>

    <!-- JavaScript para manejar la creación de cotizaciones -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const createQuoteForm = document.getElementById('createQuoteForm');

            createQuoteForm.addEventListener('submit', function(event) {
                event.preventDefault();
                let serviceIds = [];
                let productoIds = [];
                $('#selectedServices li').each(function(index) {
                    serviceIds.push($(this).attr('data-id'));
                });
                $('#selectedProducts li').each(function(index) {
                    productoIds.push($(this).attr('data-id'));
                });

                const formData = new FormData();
                formData.append('client_name', $('#quoteClientName').val());
                formData.append('service_ids', serviceIds.join(', '));
                formData.append('product_ids', productoIds.join(', '));
                formData.append('amount', $('#invoiceAmount').val());
                formData.append('status', 'pending');
                formData.append('expiration_date', $('#quoteExpirationDate').val());

                fetch('/cotizacion', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(() => {
                        $('#createQuoteModal').modal('hide');
                        window.location.reload();
                    })
                    .catch(error => console.error('Error al enviar la solicitud:', error));
            });
        });
    </script>

    <!-- JavaScript para editar y eliminar cotizaciones -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-quote');
            const deleteButtons = document.querySelectorAll('.delete-quote');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const quoteId = button.getAttribute('data-quote-id');
                    $('#editQuoteId').val(quoteId);
                    $('#editQuoteModal').modal('show');
                });
            });

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const quoteId = button.getAttribute('data-quote-id');
                    if (confirm(`¿Estás seguro de eliminar esa cotización?`)) {
                        fetch(`/cotizacion/${quoteId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                            })
                            .then(() => window.location.reload())
                            .catch(error => console.error('Error al enviar la solicitud:', error));
                    }
                });
            });

            const editQuoteForm = document.getElementById('editQuoteForm');

            editQuoteForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const quoteId = $('#editQuoteId').val();
                const status = $('#quoteStatus').val();

                fetch(`/cotizacion/${quoteId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            status
                        })
                    })
                    .then(() => {
                        $('#editQuoteModal').modal('hide');
                        window.location.reload();
                    })
                    .catch(error => console.error('Error al enviar la solicitud:', error));
            });
        });
    </script>
</body>

</html>