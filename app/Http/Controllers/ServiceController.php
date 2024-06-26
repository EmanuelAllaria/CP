<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();

        return view('services', ['services' => $services]);
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
