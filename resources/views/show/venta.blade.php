<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Venta</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos adicionales -->
    <style>
        body {
            font-family: 'figtree', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .sale-details-card {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .sale-details-card .card-header {
            background-color: #007bff;
            color: #fff;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .sale-details-card .card-body {
            padding: 20px;
        }

        .sale-details-card p {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card sale-details-card">
            <div class="card-header">
                Detalles de la Venta
            </div>
            <div class="card-body">
                <p><strong>ID de Venta:</strong> {{ $venta->id }}</p>
                <p><strong>ID del Cliente:</strong> {{ $venta->cliente_id }}</p>
                <p><strong>ID del Producto:</strong> {{ $venta->producto_id }}</p>
                <p><strong>Cantidad:</strong> {{ $venta->cantidad }}</p>
            </div>
        </div>
    </div>
</body>

</html>