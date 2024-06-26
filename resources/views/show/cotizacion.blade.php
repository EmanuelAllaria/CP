<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CP | Detalle de Cotización</title>

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

        .quote-details {
            margin-bottom: 20px;
        }

        .quote-details p {
            margin: 5px 0;
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

        .quote-summary {
            text-align: right;
            margin-top: 20px;
        }

        .quote-summary p {
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
            <h1>Cotización</h1>
        </div>

        <div class="">
            <div class="quote-details">
                <p><strong>CP</strong></p>
                <p>Control Panel</p>
                <p>allariemanuel@gmail.com</p>
                <p>1136633061</p>
            </div>
        </div>

        <hr>

        <div class="d-flex align-items-start justify-content-between">
            <div class="quote-details">
                <p><strong>Cliente:</strong></p>
                <p>{{ $cotizacion['client_name'] }}</p>
            </div>

            <div class="quote-details">
                <p><strong>Cotización N°:</strong> #{{ $cotizacion['id'] }}</p>
                <p><strong>Fecha:</strong> {{ date('d/m/Y', strtotime($cotizacion['created_at'])) }}</p>
                @if (isset($cotizacion['expiration_date']))
                <p><strong>Válido Hasta:</strong> {{ date('d/m/Y', strtotime($cotizacion['expiration_date'])) }}</p>
                @endif
                <p><strong>Estado:</strong> <span class="status">{{ $cotizacion['status'] === 'approved' ? 'Aprobada' : ($cotizacion['status'] === 'pending' ? 'Pendiente' : 'Rechazada') }}</span></p>
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

        <div class="quote-summary">
            <p><strong>Total Cotizado:</strong> ${{ number_format($cotizacion['amount'], 2, ',', '.') }}</p>
        </div>

        <div class="footer">
            <p>Gracias por su interés en nuestros servicios.</p>
        </div>
    </div>
</body>

</html>