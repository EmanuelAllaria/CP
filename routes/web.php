<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
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

Route::get('/dashboard/user_id/{user_id}', [DashboardController::class, 'index']);
Route::get('/proyectos', [ProyectoController::class, 'index']);

// Invoice
Route::get('/invoices/user_id/{user_id}', [InvoiceController::class, 'index']);
Route::get('/invoice/{id}', [InvoiceController::class, 'show']);
Route::post('/invoice', [InvoiceController::class, 'store']);
Route::post('/invoice/{id}', [InvoiceController::class, 'update']);

// Proyecto
Route::get('/proyectos/user_id/{user_id}', [ProyectoController::class, 'index']);
Route::post('/proyecto', [ProyectoController::class, 'store']);

// Tarea
Route::get('/tareas/user_id/{user_id}', [TareaController::class, 'index']);
Route::post('/tarea', [TareaController::class, 'store']);
Route::post('/tarea/{id}', [TareaController::class, 'update']);

// Client
Route::get('/clients/user_id/{user_id}', [ContactController::class, 'index']);
Route::post('/client', [ContactController::class, 'store']);
Route::post('/client/uploadExcel', [ContactController::class, 'uploadExcel']);

// Service
Route::get('/services/user_id/{user_id}', [ServiceController::class, 'index']);
Route::post('/service', [ServiceController::class, 'store']);

// Producto
Route::get('/productos/user_id/{user_id}', [ProductoController::class, 'index']);
Route::get('/producto/{id}', [ProductoController::class, 'show']);
Route::post('/producto', [ProductoController::class, 'store']);
Route::post('/producto/{id}', [ProductoController::class, 'update']);
Route::post('/productos/uploadExcel', [ProductoController::class, 'uploadExcel']);

// Leads
Route::get('/leads/user_id/{user_id}', [LeadController::class, 'gestionarLeads'])->name('leads.index');
Route::post('/lead', [LeadController::class, 'crearLead'])->name('leads.crear');
Route::get('/lead/{lead}/convertir', [LeadController::class, 'convertirLead'])->name('leads.convertir');
Route::post('/automatizar-venta', [LeadController::class, 'automatizarVenta'])->name('ventas.automatizar');
