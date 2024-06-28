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

        // Obtener el inicio y fin del mes pasado
        $startDateMesPasado = Carbon::now()->subMonth()->startOfMonth();
        $endDateMesPasado = Carbon::now()->subMonth()->endOfMonth();

        // Calcular el total de los montos de las facturas del mes pasado
        $totalInvoicesMesPasado = Invoice::where('user_id', $user_id)
            ->where('status', 'paid')
            ->where('updated_at', '>=', $startDateMesPasado)
            ->where('updated_at', '<=', $endDateMesPasado)
            ->where('created_at', '<=', $endDateMesPasado)
            ->sum('amount');

        if ($totalInvoicesMesPasado != 0) {
            $porcentajeEsteMesInvoice = (($totalInvoices - $totalInvoicesMesPasado) / $totalInvoicesMesPasado) * 100;
        } else {
            $porcentajeEsteMesInvoice = $totalInvoices > 0 ? 100 : 0;
        }

        $totalContacts = Contact::where('user_id', $user_id)
            ->where('active', 1)
            ->where(function ($query) use ($hoy) {
                $query->whereDate('created_at', '<=', $hoy->toDateString())
                    ->whereMonth('created_at', $hoy->month);
            })
            ->count();

        $totalContactsMesPasado = Contact::where('user_id', $user_id)
            ->where('active', 1)
            ->where(function ($query) use ($startDate, $startDateMesPasado) {
                $query->whereDate('created_at', '<', $startDate->toDateString())
                    ->whereMonth('created_at', '>=', $startDateMesPasado->toDateString())
                    ->whereMonth('created_at', $startDateMesPasado->month);
            })
            ->count();

        if ($totalContactsMesPasado != 0) {
            $porcentajeEsteMesClients = (($totalContacts - $totalContactsMesPasado) / $totalContactsMesPasado) * 100;
        } else {
            $porcentajeEsteMesClients = $totalContacts > 0 ? 100 : 0;
        }

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

        $totalProjectsActivasMesPasado = Proyecto::where('user_id', $user_id)
            ->where(function ($query) use ($startDate, $startDateMesPasado, $endDateMesPasado) {
                $query->where(function ($query) use ($startDate, $startDateMesPasado) {
                    $query->whereDate('fecha_fin', '<', $startDate->toDateString())
                        ->whereMonth('fecha_fin', $startDateMesPasado->month);
                })
                    ->orWhere(function ($query) use ($endDateMesPasado) {
                        $query->whereMonth('fecha_fin', $endDateMesPasado->month)
                            ->whereDay('fecha_fin', '<', $endDateMesPasado->day);
                    })
                    ->orWhere(function ($query) use ($startDateMesPasado) {
                        $query->whereMonth('fecha_inicio', $startDateMesPasado->month);
                    });
            })
            ->count();

        if ($totalProjectsActivasMesPasado != 0) {
            $porcentajeEsteMesProjects = (($totalProjectsActivas - $totalProjectsActivasMesPasado) / $totalProjectsActivasMesPasado) * 100;
        } else {
            $porcentajeEsteMesProjects = $totalProjectsActivas > 0 ? 100 : 0;
        }

        $totalTareasCompletadas = Tarea::where('user_id', $user_id)
            ->where(function ($query) use ($hoy) {
                $query->whereDate('created_at', '<=', $hoy->toDateString())
                    ->whereMonth('created_at', $hoy->month);
            })
            ->where(function ($query) use ($hoy) {
                $query->whereDate('fecha_limite', '>', $hoy->toDateString())
                    ->orWhereNull('fecha_limite');
            })
            ->where('completada', 1)
            ->count();

        $totalTareasCompletadasMesPasado = Tarea::where('user_id', $user_id)
            ->where(function ($query) use ($startDate, $startDateMesPasado) {
                $query->whereDate('created_at', '<', $startDate->toDateString())
                    ->whereMonth('created_at', $startDateMesPasado->month);
            })
            ->where(function ($query) use ($startDateMesPasado, $endDateMesPasado) {
                $query->whereDate('fecha_limite', '>', $startDateMesPasado->toDateString())
                    ->whereDate('fecha_limite', '<', $endDateMesPasado->toDateString())
                    ->orWhereNull('fecha_limite');
            })
            ->where('completada', 1)
            ->count();

        if ($totalTareasCompletadasMesPasado != 0) {
            $porcentajeEsteMesTareasCompletadas = (($totalTareasCompletadas - $totalTareasCompletadasMesPasado) / $totalTareasCompletadasMesPasado) * 100;
        } else {
            $porcentajeEsteMesTareasCompletadas = $totalTareasCompletadas > 0 ? 100 : 0;
        }

        $totalTareasNoCompletadas = Tarea::where('user_id', $user_id)
            ->where(function ($query) use ($startDate, $startDateMesPasado) {
                $query->whereDate('created_at', '<', $startDate->format('Y-m-d'));
                $query->whereDate('created_at', '>', $startDateMesPasado->format('Y-m-d'));
            })
            ->where(function ($query) use ($startDate, $startDateMesPasado) {
                $query->whereDate('fecha_limite', '>', $startDateMesPasado->format('Y-m-d'))
                    ->whereDate('fecha_limite', '<', $startDate->format('Y-m-d'))
                    ->orWhereNull('fecha_limite');
            })
            ->where('completada', 0)
            ->count();

        $totalTareasNoCompletadasMesPasado = Tarea::where('user_id', $user_id)
            ->where(function ($query) use ($hoy) {
                $query->whereDate('created_at', '<=', $hoy->format('Y-m-d'));
            })
            ->where(function ($query) use ($hoy) {
                $query->whereDate('fecha_limite', '>', $hoy->format('Y-m-d'))
                    ->orWhereNull('fecha_limite');
            })
            ->where('completada', 0)
            ->count();

        if ($totalTareasNoCompletadasMesPasado != 0) {
            $porcentajeEsteMesTareasNoCompletadas = (($totalTareasNoCompletadas - $totalTareasNoCompletadasMesPasado) / $totalTareasNoCompletadasMesPasado) * 100;
        } else {
            $porcentajeEsteMesTareasNoCompletadas = $totalTareasNoCompletadas > 0 ? 100 : 0;
        }

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
            'porcentajeEsteMesClients' => $porcentajeEsteMesClients,
            'totalProjectsActivas' => $totalProjectsActivas,
            'porcentajeEsteMesProjects' => $porcentajeEsteMesProjects,
            'totalInvoices' => floatval($totalInvoices),
            'porcentajeEsteMesInvoice' => $porcentajeEsteMesInvoice,
            'totalTareasCompletadas' => $totalTareasCompletadas,
            'porcentajeEsteMesTareasCompletadas' => $porcentajeEsteMesTareasCompletadas,
            'totalTareasNoCompletadas' => $totalTareasNoCompletadas,
            'porcentajeEsteMesTareasNoCompletadas' => $porcentajeEsteMesTareasNoCompletadas,
            'totalProductos' => $totalProductos,
            'recentClients' => $recentClients,
            'recentProjects' => $recentProjects,
            'recentTareas' => $recentTareas,
            'user_id' => $user_id,
        ];

        return view('dashboard', ['data' => $data]);
    }
}
