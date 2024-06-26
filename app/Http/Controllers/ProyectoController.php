<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\User;

class ProyectoController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return response()->json(['mensaje' => 'Hubo un error, inicie sesion nuevamente porfavor.'], 404);
        }
        $user_id = auth()->id();

        $proyectos = Proyecto::where('user_id', $user_id)->get();
        return view('proyectos', ['proyectos' => $proyectos, 'user_id' => $user_id]);
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
