<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CP | Detalle de Factura</title>

    <!-- Bootstrap CSS (opcional, si se desea) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f7f7f7;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header img {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        .invoice-details p {
            margin: 5px 0;
        }

        .invoice-details .status {
            font-weight: bold;
            text-transform: uppercase;
            color: green;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.8rem;
            color: #888;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .invoice-summary {
            text-align: right;
            margin-top: 20px;
        }

        .invoice-summary p {
            margin: 5px 0;
        }

        @media print {
            body {
                background-color: #fff;
            }

            .container {
                box-shadow: none;
                border: 1px solid #ddd;
                padding: 10px;
            }

            .footer {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Factura</h1>
        </div>

        <div class="">
            <div class="invoice-details">
                <p><strong>CP</strong></p>
                <p>Control Panel</p>
                <p>allariemanuel@gmail.com</p>
                <p>1136633061</p>
            </div>
        </div>

        <hr>

        <div class="d-flex align-items-start justify-content-between">
            <div class="invoice-details">
                <p><strong>Cliente:</strong></p>
                <p>{{ $invoice['cliente']['name'] }}</p>
                <p>{{ $invoice['cliente']['company'] }}</p>
                <p>{{ $invoice['cliente']['email'] }}</p>
                <p>{{ $invoice['cliente']['phone'] }}</p>
            </div>

            <div class="invoice-details">
                <p><strong>Factura N°:</strong> #{{ $invoice['id'] }}</p>
                <p><strong>Fecha:</strong> {{ date('d/m/Y', strtotime($invoice['created_at'])) }}</p>
                @if (isset($invoice['due_date']))
                <p><strong>Vencimiento:</strong> {{ date('d/m/Y', strtotime($invoice['due_date'])) }}</p>
                @endif
                <p><strong>Estado del pago:</strong> <span class="status">{{ $invoice['status'] === 'paid' ? 'Pagado' : 'Falta Pagar' }}</span></p>
            </div>
        </div>

        @if (isset($servicios_data[0]))
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre del Servicio</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servicios_data as $index => $servicio)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $servicio['name'] }}</td>
                    <td>{{ $servicio['quantity'] }}</td>
                    <td>${{ number_format($servicio['price'], 2, ',', '.') }}</td>
                    <td>${{ number_format($servicio['price'] * $servicio['quantity'], 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @elseif (isset($productos_data[0]))
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre del Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos_data as $index => $producto)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $producto['name'] }}</td>
                    <td>{{ $producto['quantity'] }}</td>
                    <td>${{ number_format($producto['price'], 2, ',', '.') }}</td>
                    <td>${{ number_format($producto['price'] * $producto['quantity'], 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <div class="invoice-summary">
            <p><strong>Método de Pago:</strong> {{ ucwords($invoice['tipo_pago']) }}</p>
            <p><strong>Cantidad Pagada:</strong> ${{ number_format($invoice['amount'], 2, ',', '.') }}</p>
            <p><strong>Cantidad Adeudada:</strong> ${{ number_format($invoice['amount_missing'], 2, ',', '.') }}</p>
        </div>

        <div class="footer">
            <p>Gracias por su compra.</p>
        </div>
    </div>
</body>

</html>