<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TareaController;
use Illuminate\Support\Facades\Route;

// Login and Register
Route::get('/', function () {
    return view('login');
})->name('login');
Route::get('/register', function () {
    return view('register');
})->name('register');
Route::post('/login-post', [UserController::class, 'login']);
Route::post('/register-post', [UserController::class, 'register']);

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/proyectos', [ProyectoController::class, 'index']);

// Invoice
Route::get('/invoices', [InvoiceController::class, 'index']);
Route::get('/invoice/{id}', [InvoiceController::class, 'show']);
Route::post('/invoice', [InvoiceController::class, 'store']);
Route::post('/invoice/{id}', [InvoiceController::class, 'update']);

// Proyecto
Route::get('/proyectos', [ProyectoController::class, 'index']);
Route::post('/proyecto', [ProyectoController::class, 'store']);

// Tarea
Route::get('/tareas', [TareaController::class, 'index']);
Route::post('/tarea', [TareaController::class, 'store']);
Route::post('/tarea/{id}', [TareaController::class, 'update']);

// Client
Route::get('/clients', [ContactController::class, 'index']);
Route::post('/client', [ContactController::class, 'store']);
Route::post('/client/uploadExcel', [ContactController::class, 'uploadExcel']);

// Service
Route::get('/services', [ServiceController::class, 'index']);
Route::post('/service', [ServiceController::class, 'store']);

// Producto
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/producto/{id}', [ProductoController::class, 'show']);
Route::post('/producto', [ProductoController::class, 'store']);
Route::post('/producto/{id}', [ProductoController::class, 'update']);
Route::post('/productos/uploadExcel', [ProductoController::class, 'uploadExcel']);

// Lead
Route::get('/leads', [LeadController::class, 'gestionarLeads'])->name('leads.index');
Route::post('/lead', [LeadController::class, 'crearLead'])->name('leads.crear');
Route::get('/lead/{lead}/convertir', [LeadController::class, 'convertirLead'])->name('leads.convertir');
Route::post('/automatizar-venta', [LeadController::class, 'automatizarVenta'])->name('ventas.automatizar');

// Cotizacion
Route::get('cotizaciones', [CotizacionController::class, 'index']);
Route::get('cotizacion/{id}', [CotizacionController::class, 'show']);
Route::post('cotizacion', [CotizacionController::class, 'store']);
Route::post('cotizacion/{id}', [CotizacionController::class, 'update']);
Route::delete('cotizacion/{id}', [CotizacionController::class, 'destroy']);
