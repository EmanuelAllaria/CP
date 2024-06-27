<?php

use Carbon\Carbon;

$hoy = Carbon::now();

$user_id = $data['user_id'];
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="scrollbar-width: none;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CP | Control Panel</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/15fbf0e0d4.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: 'inter', sans-serif;
            background-color: #11121E;
            padding: 20px;
            margin-left: 200px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-column-gap: 1em;
            grid-row-gap: 1em;
        }

        .stat-item {
            background-color: #1d1d29;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .stat-item h2 {
            font-size: 1rem;
            margin-bottom: 10px;
            color: #fff;
        }

        .stat-item p {
            color: #fff;
            font-size: 2rem;
            font-weight: bold;
            margin: 0;
        }

        .stat-item p:hover {
            animation: bounce 1s;
        }

        .data-list {
            margin-top: 30px;
        }

        .data-list h2 {
            font-size: 1.5rem;
            color: #007bff;
        }

        .data-list table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .data-list table th,
        .data-list table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: left;
            color: #fff;
        }

        .data-list table th {
            background-color: #1c1c28;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-15px);
            }

            60% {
                transform: translateY(-7px);
            }
        }
    </style>
</head>

<body>
    @include('header')

    <div class="container">
        <h1 class="mt-4 mb-4 text-white">Dashboard</h1>

        <div class="stats">
            <div class="stat-item">
                <h2>Nuevos Clientes</h2>
                <p style="width:max-content;">{{ $data['totalContacts'] }}</p>
                <span style="font-size:0.7em;<?php echo $data['porcentajeEsteMesClients'] > 0 ? 'color:green;' : ($data['porcentajeEsteMesClients'] < 0 ? 'color:red;' : 'color:yellow;') ?>"><?php echo $data['porcentajeEsteMesClients'] > 0 ? '<i class="fa-solid fa-arrow-up"></i> ' : ($data['porcentajeEsteMesClients'] < 0 ? '<i class="fa-solid fa-arrow-down"></i> ' : '') ?>{{ sprintf("%.2f%%", $data['porcentajeEsteMesClients']) }} del mes pasado.</span>
            </div>
            <div class="stat-item" style="position:relative;">
                <h2 class="message-proyects-activator">Proyectos Activos</h2>
                <p style="width:max-content;">{{ $data['totalProjectsActivas'] }}</p>
                <span style="font-size:0.7em;<?php echo $data['porcentajeEsteMesProjects'] > 0 ? 'color:green;' : ($data['porcentajeEsteMesProjects'] < 0 ? 'color:red;' : 'color:yellow;') ?>"><?php echo $data['porcentajeEsteMesProjects'] > 0 ? '<i class="fa-solid fa-arrow-up"></i> ' : ($data['porcentajeEsteMesProjects'] < 0 ? '<i class="fa-solid fa-arrow-down"></i> ' : '') ?>{{ sprintf("%.2f%%", $data['porcentajeEsteMesProjects']) }} del mes pasado.</span>
                <div class="message-proyects" style="position: absolute;background: red;color: #fff;padding: 0.5em;border-radius: 10px;border: 3px solid rgb(0, 0, 0);top: 30%;left: 0;display: none;">
                    <p style="font-size:0.8em;">Los proyectos activos sin fecha de fin están activos solo este mes; se actualizan mensualmente.</p>
                </div>
            </div>
            <div class="stat-item">
                <h2>Tareas Completadas</h2>
                <p style="width:max-content;">{{ $data['totalTareasCompletadas'] }}</p>
                <span style="font-size:0.7em;<?php echo $data['porcentajeEsteMesTareasCompletadas'] > 0 ? 'color:green;' : ($data['porcentajeEsteMesTareasCompletadas'] < 0 ? 'color:red;' : 'color:yellow;') ?>"><?php echo $data['porcentajeEsteMesTareasCompletadas'] > 0 ? '<i class="fa-solid fa-arrow-up"></i> ' : ($data['porcentajeEsteMesTareasCompletadas'] < 0 ? '<i class="fa-solid fa-arrow-down"></i> ' : '') ?>{{ sprintf("%.2f%%", $data['porcentajeEsteMesTareasCompletadas']) }} del mes pasado.</span>
            </div>
            <div class="stat-item">
                <h2>Tareas No Completadas</h2>
                <p style="width:max-content;">{{ $data['totalTareasNoCompletadas'] }}</p>
                <span style="font-size:0.7em;<?php echo $data['porcentajeEsteMesTareasNoCompletadas'] > 0 ? 'color:green;' : ($data['porcentajeEsteMesTareasNoCompletadas'] < 0 ? 'color:red;' : 'color:yellow;') ?>"><?php echo $data['porcentajeEsteMesTareasNoCompletadas'] > 0 ? '<i class="fa-solid fa-arrow-up"></i> ' : ($data['porcentajeEsteMesTareasNoCompletadas'] < 0 ? '<i class="fa-solid fa-arrow-down"></i> ' : '') ?>{{ sprintf("%.2f%%", $data['porcentajeEsteMesTareasNoCompletadas']) }} del mes pasado.</span>
            </div>
            <div class="stat-item" style="position:relative;">
                <h2 class="message-invoice-activator">Fondos Recibidos</h2>
                <p style="color:green;width:max-content;">${{ number_format($data['totalInvoices'], 2, ',', '.') }}</p>
                <span style="font-size:0.7em;<?php echo $data['porcentajeEsteMesInvoice'] > 0 ? 'color:green;' : ($data['porcentajeEsteMesInvoice'] < 0 ? 'color:red;' : 'color:yellow;') ?>"><?php echo $data['porcentajeEsteMesInvoice'] > 0 ? '<i class="fa-solid fa-arrow-up"></i> ' : ($data['porcentajeEsteMesInvoice'] < 0 ? '<i class="fa-solid fa-arrow-down"></i> ' : '') ?>{{ sprintf("%.2f%%", $data['porcentajeEsteMesInvoice']) }} del mes pasado.</span>
                <div class="message-invoice" style="position: absolute;background: red;color: #fff;padding: 0.5em;border-radius: 10px;border: 3px solid rgb(0, 0, 0);top: 30%;left: 0;display: none;">
                    <p style="font-size:0.8em;">Las facturas sin fecha de vencimientio se suman solo las creadas este mes; se actualizan mensualmente.</p>
                </div>
            </div>
            <div class="stat-item">
                <h2>Productos Totales</h2>
                <p style="width:max-content;">{{ $data['totalProductos'] }}</p>
            </div>
        </div>

        <div class="data-list">
            <h2 class="text-white">Últimos Clientes</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Empresa</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($data['recentClients'][0]['id']))
                    @foreach ($data['recentClients'] as $client)
                    <tr>
                        <td>{{ $client['name'] }}</td>
                        <td>{{ $client['company'] }}</td>
                        <td>{{ $client['email'] }}</td>
                        <td>{{ $client['phone'] }}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="4" class="text-center">No hay clientes.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="data-list">
            <h2 class="text-white">Últimos Proyectos</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Presupuesto</th>
                        <th>Activo</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($data['recentProjects'][0]['id']))
                    @foreach ($data['recentProjects'] as $project)
                    <tr>
                        <td>{{ $project['nombre'] }}</td>
                        <td>{{ $project['descripcion'] }}</td>
                        <td>{{ $project['fecha_inicio'] }}</td>
                        <td>{{ $project['fecha_fin'] }}</td>
                        <td>${{ number_format($project['presupuesto'], 2, ',', '.') }}</td>
                        <td>{{ (Carbon::parse($project['fecha_fin'])->lessThanOrEqualTo($hoy) || Carbon::parse($project['fecha_fin'])->month === $hoy->month) && Carbon::parse($project['fecha_fin'])->day < $hoy->day ? 'No' : 'Si' }}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="6" class="text-center">No hay proyectos.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="data-list">
            <h2 class="text-white">Últimas Tareas</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha Límite</th>
                        <th>Completada</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($data['recentTareas'][0]['id']))
                    @foreach ($data['recentTareas'] as $task)
                    <tr>
                        <td>{{ $task['nombre'] }}</td>
                        <td>{{ $task['descripcion'] }}</td>
                        <td>{{ $task['fecha_limite'] }}</td>
                        <td>{{ $task['completada'] ? 'Sí' : 'No' }}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="4" class="text-center">No hay Tareas.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.message-proyects').hide();

            $('.message-proyects-activator').mouseenter(function() {
                $('.message-proyects').show();
            });

            $('.message-proyects-activator').mouseleave(function() {
                $('.message-proyects').hide();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.message-invoice').hide();

            $('.message-invoice-activator').mouseenter(function() {
                $('.message-invoice').show();
            });

            $('.message-invoice-activator').mouseleave(function() {
                $('.message-invoice').hide();
            });
        });
    </script>
</body>

</html>