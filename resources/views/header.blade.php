<style>
    .sidebar-header {
        background-color: #007bff;
        color: #fff;
        padding: 20px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 200px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar-header h1 {
        font-size: 1.5rem;
        margin-bottom: 20px;
    }

    .sidebar-stats {
        margin-top: 20px;
    }

    .sidebar-stat-item {
        background-color: #fff;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .sidebar-stat-item h2 {
        font-size: 1rem;
        margin-bottom: 5px;
        color: #007bff;
    }

    .sidebar-stat-item p {
        font-size: 1.2rem;
        font-weight: bold;
        margin: 0;
    }
</style>

<div class="sidebar-header">
    <a href="/dashboard" style="color:#fff;">
        <h1 class="text-center" style="line-height:0.7;font-size:2em;">CP
            <br> <span style="font-size:0.4em;color:#000;">CONTROL PANEL</span>
        </h1>
    </a>
    <div class="sidebar-stats">
        <div class="sidebar-stat-item">
            <h2><a href="/clients">Clientes</a></h2>
        </div>
        <div class="sidebar-stat-item">
            <h2><a href="/leads">Ventas</a></h2>
        </div>
        <div class="sidebar-stat-item">
            <h2><a href="/services">Servicios</a></h2>
        </div>
        <div class="sidebar-stat-item">
            <h2><a href="/productos">Productos</a></h2>
        </div>
        <div class="sidebar-stat-item">
            <h2><a href="/proyectos">Proyectos</a></h2>
        </div>
        <div class="sidebar-stat-item">
            <h2><a href="/tareas">Tareas</a></h2>
        </div>
        <div class="sidebar-stat-item">
            <h2><a href="/invoices">Facturas</a></h2>
        </div>
        <div class="sidebar-stat-item">
            <h2><a href="/cotizaciones">Cotizaciones</a></h2>
        </div>
    </div>
</div>