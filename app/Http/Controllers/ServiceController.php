<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;

class ServiceController extends Controller
{
    public function index($user_id = null)
    {
        if (!isset($user_id) || $user_id === '') {
            return response()->json(['mensaje' => 'Hubo un error, inicie sesion nuevamente porfavor.'], 404);
        } else {
            $user = User::find($user_id);
            if (!isset($user)) {
                return response()->json(['mensaje' => 'No existe este usuario, porfavor registrese.'], 404);
            }
        }

        $services = Service::where('user_id', $user_id)->get();

        return view('services', ['services' => $services, 'user_id' => $user_id]);
    }

    public function store(Request $request)
    {
        $service = Service::create($request->all());

        return response(['data' => $service, 'mensaje' => 'Se creó el servicio con exito'], 200);
    }

    public function show($id)
    {
        $service = Service::findOrFail($id);

        return view('show.service', ['service' => $service]);
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $service->update($request->all());

        return response(['data' => $service, 'mensaje' => 'Se modificó el servicio con exito'], 200);
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response(['data' => $service, 'mensaje' => 'Se eliminó el servicio con exito'], 200);
    }
}
