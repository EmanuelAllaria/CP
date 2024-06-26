<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Invoice;
use App\Models\Producto;
use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return response()->json(['mensaje' => 'Hubo un error, inicie sesion nuevamente porfavor.'], 404);
        }
        $user_id = auth()->id();

        // Obtener la fecha de inicio y fin del mes actual
        $hoy = Carbon::now();
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // Calcular el total de los montos de las facturas del mes actual
        $totalInvoices = Invoice::where('user_id', $user_id)
            ->where('status', 'paid')
            ->where('updated_at', '>=', $startDate)
            ->where('updated_at', '<=', $endDate)
            ->where('created_at', '<=', $endDate)
            ->sum('amount');

        $totalContacts = Contact::where('user_id', $user_id)
            ->where('active', 1)
            ->where(function ($query) use ($hoy) {
                $query->whereDate('created_at', '<=', $hoy->toDateString())
                    ->whereMonth('created_at', $hoy->month);
            })
            ->count();
        $totalProjectsActivas = Proyecto::where('user_id', $user_id)
            ->where(function ($query) use ($hoy) {
                $query->where(function ($query) use ($hoy) {
                    $query->whereDate('fecha_fin', '<=', $hoy->toDateString())
                        ->whereMonth('fecha_fin', $hoy->month);
                })
                    ->orWhere(function ($query) use ($hoy) {
                        $query->whereMonth('fecha_fin', $hoy->month)
                            ->whereDay('fecha_fin', '<', $hoy->day);
                    })
                    ->orWhere(function ($query) use ($hoy) {
                        $query->whereMonth('fecha_inicio', $hoy->month);
                    });
            })
            ->count();
        $totalTareasCompletadas = Tarea::where('user_id', $user_id)
            ->where(function ($query) use ($hoy) {
                $query->whereDate('created_at', '<=', $hoy->toDateString())
                    ->whereMonth('created_at', $hoy->month);
            })
            ->where(function ($query) use ($hoy) {
                $query->whereDate('fecha_limite', '>', $hoy->toDateString());
            })
            ->where('completada', 1)
            ->count();
        $totalTareasNoCompletadas = Tarea::where('user_id', $user_id)
            ->where(function ($query) use ($hoy) {
                $query->whereDate('created_at', '<=', $hoy->format('Y-m-d'));
            })
            ->where(function ($query) use ($hoy) {
                $query->whereDate('fecha_limite', '>', $hoy->format('Y-m-d'));
            })
            ->where('completada', 0)
            ->count();
        $totalProductos = Producto::where('user_id', $user_id)
            ->count();

        $recentClients = Contact::where('user_id', $user_id)
            ->where('active', 1)
            ->latest()->take(5)->get();
        $recentProjects = Proyecto::where('user_id', $user_id)
            ->latest()->take(5)->get();
        $recentTareas = Tarea::where('user_id', $user_id)
            ->latest()->take(5)->get();

        $data = [
            'totalContacts' => $totalContacts,
            'totalProjectsActivas' => $totalProjectsActivas,
            'totalInvoices' => floatval($totalInvoices),
            'totalTareasCompletadas' => $totalTareasCompletadas,
            'totalTareasNoCompletadas' => $totalTareasNoCompletadas,
            'totalProductos' => $totalProductos,
            'recentClients' => $recentClients,
            'recentProjects' => $recentProjects,
            'recentTareas' => $recentTareas,
            'user_id' => $user_id,
        ];

        return view('dashboard', ['data' => $data]);
    }
}
