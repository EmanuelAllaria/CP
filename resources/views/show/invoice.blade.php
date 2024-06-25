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
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        .invoice-details p {
            margin: 5px 0;
        }

        .status {
            font-weight: bold;
            text-transform: uppercase;
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
            <h1>Detalle de Factura</h1>
        </div>

        <div class="invoice-details">
            <p><strong>#</strong>{{ $invoice['id'] }}</p>
            <p><strong>Cliente:</strong> {{ $invoice['client_name'] }}</p>
            <p><strong>Empresa:</strong> {{ $invoice['client_company'] }}</p>
            @if (isset($invoice['due_date']))
            <p><strong>Fecha de Vencimiento:</strong> {{ date('d/m/Y', strtotime($invoice['due_date'])) }}</p>
            @endif
            <p><strong>Total:</strong> ${{ number_format($invoice['amount'], 2, ',', '.') }}</p>
            <p><strong>Estado:</strong> <span class="status">{{ $invoice['status'] === 'paid' ? 'Pagado' : 'Falta Pagar'  }}</span></p>
        </div>

        @if (isset($servicios_data[0]))
        <table>
            <thead>
                <tr>
                    <th>Nombre del Servicio</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servicios_data as $servicio)
                <tr>
                    <td>{{ $servicio['name'] }}</td>
                    <td>${{ number_format($servicio['price'], 2, ',', '.') }}</td>
                    <td>{{ $servicio['quantity'] }}</td>
                    <td>${{ number_format($servicio['price'] * $servicio['quantity'], 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @elseif (isset($productos_data[0]))
        <table>
            <thead>
                <tr>
                    <th>Nombre del Producto</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos_data as $producto)
                <tr>
                    <td>{{ $producto['name'] }}</td>
                    <td>${{ number_format($producto['price'], 2, ',', '.') }}</td>
                    <td>{{ $producto['quantity'] }}</td>
                    <td>${{ number_format($producto['price'] * $producto['quantity'], 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <div class="footer">
            <p>Gracias por su compra.</p>
        </div>
    </div>
</body>

</html>