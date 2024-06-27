<?php

use App\Models\Contact;
use App\Models\Service;

$servicios = Service::all();
$clients = Contact::all();
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="scrollbar-width: none;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CP | Gestión de Factura</title>

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

        table {
            width: 100%;
            color: #fff !important;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #fff;
        }

        th {
            background-color: #1d1d29;
            color: #fff;
        }

        .status-icon {
            font-size: 1.5rem;
            cursor: pointer;
        }

        .status-icon.green {
            color: green;
        }

        .status-icon.red {
            color: red;
        }
    </style>
</head>

<body>
    @include('header')

    <div class="container">
        <h1 class="mt-4 mb-4 text-white">Facturas</h1>
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createInvoiceModal">
            Crear Nueva Factura
        </button>
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($invoices) && count($invoices) > 0)
                    @foreach ($invoices as $item)
                    <tr>
                        <td>{{ $item->client_name }} <b>({{ $item->client_company }})</b></td>
                        <td>${{ number_format($item->amount, 2, ',', '.') }}</td>
                        <td class="d-flex align-items-center" style="gap:0.5em;">
                            <i data-id="{{ $item->id }}" onclick="changeStatusInvoice(this)" class="fas fa-circle status-icon {{ $item->status === 'paid' ? 'green' : 'red' }}"></i>
                            <a href="/invoice/{{ $item->id }}" target="_blank" style="color:red;"><i class="fas fa-file-invoice"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td class="text-center" colspan="3">
                            <p class="my-0">No hay Facturas creadas.</p>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para la creación de nueva factura -->
    <div class="modal fade" id="createInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="createInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createInvoiceModalLabel">Crear Nueva Factura</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createInvoiceForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="clientId">Nombre del Cliente</label>
                            <select id="clientId" class="form-control" name="client_id" required>
                                @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }} - {{ $client->company }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="invoiceService">Servicio</label>
                            <select id="invoiceService" class="form-control" required>
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
                        <div class="form-group">
                            <label for="invoiceAmount">Total</label>
                            <input type="number" class="form-control" id="invoiceAmount" name="amount" step="0.01" min="0" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="invoiceDueDate">Fecha de Vencimiento</label>
                            <input type="date" class="form-control" id="invoiceDueDate" name="due_date">
                        </div>
                        <div class="form-group">
                            <label for="invoiceStatus">Estado</label>
                            <select class="form-control" id="invoiceStatus" name="status" required>
                                <option value="unpaid">No pagado</option>
                                <option value="paid">Pagado</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Factura</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('footer')

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
            const createInvoiceForm = document.getElementById('createInvoiceForm');

            createInvoiceForm.addEventListener('submit', function(event) {
                event.preventDefault();

                let serviceIds = [];
                $('#selectedServices li').each(function(index) {
                    serviceIds.push($(this).attr('data-id'));
                });

                const formData = new FormData();
                formData.append('servicio_ids', serviceIds.join(', '));
                formData.append('client_id', $('#clientId').val());
                formData.append('amount', $('#invoiceAmount').val());
                formData.append('due_date', $('#invoiceDueDate').val());
                formData.append('status', $('#invoiceStatus').val());
                formData.append('user_id', <?php echo $user_id ?>);

                // Send AJAX request to create invoice
                fetch('/invoice', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        $('#createInvoiceModal').modal('hide');
                        window.location.reload();
                    })
                    .catch(error => console.error('Error al enviar la solicitud:', error));
            });
        });
    </script>

    <script>
        function changeStatusInvoice(icon) {
            const invoiceId = icon.getAttribute('data-id');
            const newStatus = icon.classList.contains('green') ? 'unpaid' : 'paid';

            fetch(`/invoice/${invoiceId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(() => window.location.reload())
                .catch(error => console.error('Error al enviar la solicitud:', error));
        };
    </script>
</body>

</html>