<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Tarea;
use Illuminate\Support\Facades\DB;

class TareaController extends Controller
{
    public function index()
    {
        $tareas = DB::table('tareas')
            ->join('proyectos', 'tareas.proyecto_id', '=', 'proyectos.id')
            ->select('tareas.*', 'proyectos.nombre as nombre_proyecto')
            ->get();

        return view('tareas', ['tareas' => $tareas]);
    }

    public function store(Request $request)
    {
        $tarea = Tarea::create($request->all());

        return response(['data' => $tarea, 'mensaje' => 'Se creó la tarea con exito'], 200);
    }

    public function show($id)
    {
        $tarea = Tarea::findOrFail($id);

        return view('show.tarea', ['tarea' => $tarea]);
    }

    public function update(Request $request, $id)
    {
        $tarea = Tarea::findOrFail($id);
        $tarea->update($request->all());

        return response(['data' => $tarea, 'mensaje' => 'Se actualizó la tarea con exito'], 200);
    }

    public function destroy($proyecto_id, $tarea_id)
    {
        $tarea = Tarea::findOrFail($tarea_id);
        $tarea->delete();

        $proyecto = Proyecto::findOrFail($proyecto_id);
        return view('tareas', ['tareas' => $proyecto->tareas]);
    }
}
