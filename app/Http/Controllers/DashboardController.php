<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Invoice;
use App\Models\Proyecto;
use App\Models\Tarea;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener la fecha de inicio y fin del mes actual
        $hoy = Carbon::now();
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        // Calcular el total de los montos de las facturas del mes actual
        $totalInvoices = Invoice::where('status', 'paid')
            ->where('due_date', '>=', $startDate)
            ->sum('amount');

        $totalContacts = Contact::where(function ($query) use ($hoy) {
            $query->whereDate('created_at', '<=', $hoy->toDateString())
                ->whereMonth('created_at', $hoy->month);
        })
            ->count();
        $totalProjectsActivas = Proyecto::where(function ($query) use ($hoy) {
            $query->where(function ($query) use ($hoy) {
                $query->whereDate('fecha_fin', '<=', $hoy->toDateString())
                    ->whereMonth('fecha_fin', $hoy->month);
            })
                ->orWhere(function ($query) use ($hoy) {
                    $query->whereMonth('fecha_fin', $hoy->month)
                        ->whereDay('fecha_fin', '<', $hoy->day);
                });
        })
            ->count();
        $totalTareasCompletadas = Tarea::where(function ($query) use ($hoy) {
            $query->whereDate('created_at', '<=', $hoy->toDateString())
                ->whereMonth('created_at', $hoy->month);
        })
            ->where(function ($query) use ($hoy) {
                $query->whereDate('fecha_limite', '>', $hoy->toDateString());
            })
            ->where('completada', 1)
            ->count();
        $totalTareasNoCompletadas = Tarea::where(function ($query) use ($hoy) {
            $query->whereDate('created_at', '<=', $hoy->format('Y-m-d'));
        })
            ->where(function ($query) use ($hoy) {
                $query->whereDate('fecha_limite', '>', $hoy->format('Y-m-d'));
            })
            ->where('completada', 0)
            ->count();
        $totalProductos = Tarea::count();

        $recentClients = Contact::latest()->take(5)->get();
        $recentProjects = Proyecto::latest()->take(5)->get();
        $recentTareas = Tarea::latest()->take(5)->get();

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
        ];

        return view('dashboard', ['data' => $data]);
    }
}
