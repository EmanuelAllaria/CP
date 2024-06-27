<?php

use Illuminate\Support\Facades\Request;
?>

<style>
    .sidebar-header {
        background-color: #1D1D29;
        color: #fff;
        padding: 20px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 200px;
        overflow-y: auto;
        scrollbar-width: none;
    }

    .sidebar-header h1 {
        font-size: 1.5rem;
        margin-bottom: 20px;
    }

    .sidebar-stat-item {
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .sidebar-stat-item.active,
    .sidebar-stat-item:hover {
        background-color: #fff;
    }

    .sidebar-stat-item h2 {
        font-size: 1rem;
        margin-bottom: 0;
        padding-left: 10px;
    }

    .sidebar-stat-item h2 a {
        color: #fff;
        FONT-WEIGHT: 900;
        text-decoration: none;
    }

    .sidebar-stat-item.active h2 a,
    .sidebar-stat-item:hover h2 a {
        color: #7B7B7B;
    }

    .sidebar-stat-item p {
        font-size: 1.2rem;
        font-weight: bold;
        margin: 0;
    }
</style>

<div class="sidebar-header">
    <a href="/dashboard" style="color:#fff;">
        <h1 class="text-center" style="line-height:0.7;font-size:2em;">CP</h1>
    </a>
    <div class="sidebar-stats" style="margin-top: 2em;">
        <div class="sidebar-stat-item <?php echo Request::is('dashboard') ? 'active' : '' ?>">
            <h2><a href="/dashboard">Dashboard</a></h2>
        </div>
        <div class="sidebar-stat-item <?php echo Request::is('clients') ? 'active' : '' ?>">
            <h2><a href="/clients">Clientes</a></h2>
        </div>
        <div class="sidebar-stat-item <?php echo Request::is('leads') ? 'active' : '' ?>">
            <h2><a href="/leads">Ventas</a></h2>
        </div>
        <div class="sidebar-stat-item <?php echo Request::is('services') ? 'active' : '' ?>">
            <h2><a href="/services">Servicios</a></h2>
        </div>
        <div class="sidebar-stat-item <?php echo Request::is('productos') ? 'active' : '' ?>">
            <h2><a href="/productos">Productos</a></h2>
        </div>
        <div class="sidebar-stat-item <?php echo Request::is('proyectos') ? 'active' : '' ?>">
            <h2><a href="/proyectos">Proyectos</a></h2>
        </div>
        <div class="sidebar-stat-item <?php echo Request::is('tareas') ? 'active' : '' ?>">
            <h2><a href="/tareas">Tareas</a></h2>
        </div>
        <div class="sidebar-stat-item <?php echo Request::is('invoices') ? 'active' : '' ?>">
            <h2><a href="/invoices">Facturas</a></h2>
        </div>
        <div class="sidebar-stat-item <?php echo Request::is('cotizaciones') ? 'active' : '' ?>">
            <h2><a href="/cotizaciones">Cotizaciones</a></h2>
        </div>
    </div>
    <hr style="border-color:#595960;border-width:3px;">
    <div class="sidebar-stats">
        <div class="sidebar-stat-item <?php echo Request::is('configuracion') ? 'active' : '' ?>">
            <h2><a href="/configuracion">Configuración</a></h2>
        </div>
        <div class="sidebar-stat-item">
            <h2><a href="/logout">Cerrar Sesión</a></h2>
        </div>
    </div>
</div>