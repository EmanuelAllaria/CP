<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;

class ProyectoController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::all();
        return view('proyectos', ['proyectos' => $proyectos]);
    }

    public function store(Request $request)
    {
        $proyecto = Proyecto::create($request->all());

        return response(['data' => $proyecto, 'mensaje' => 'Se creó el proyecto con exito'], 200);
    }

    public function show($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        return view('show.proyecto', ['proyecto' => $proyecto]);
    }

    public function update(Request $request, $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->update($request->all());

        return view('update.proyecto', ['proyecto' => $proyecto]);
    }

    public function destroy($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->delete();

        $proyectos = Proyecto::all();
        return view('proyecto', ['proyectos' => $proyectos]);
    }
}
