<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cotizacion;
use App\Models\Producto;
use App\Models\Service;

class CotizacionController extends Controller
{
    public function index()
    {
        $cotizacions = Cotizacion::all();
        return view('cotizaciones', ['cotizaciones' => $cotizacions]);
    }

    public function store(Request $request)
    {
        $cotizacion = Cotizacion::create($request->all());

        return response(['data' => $cotizacion, 'mensaje' => 'Se creó la cotización con exito'], 200);
    }

    public function show($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);

        $productos_data = [];
        $servicios_data = [];

        if (isset($cotizacion->service_ids)) {
            $servicio_ids = explode(', ', $cotizacion->service_ids);

            $servicio_counts = array_count_values($servicio_ids);

            $servicios = Service::whereIn('id', array_keys($servicio_counts))->get();

            foreach ($servicios as $servicio) {
                $servicios_data[] = [
                    'name' => $servicio->name,
                    'price' => $servicio->price,
                    'quantity' => $servicio_counts[$servicio->id],
                ];
            }
        } elseif (isset($cotizacion->product_ids)) {
            $producto_ids = explode(', ', $cotizacion->product_ids);

            $producto_counts = array_count_values($producto_ids);

            $productos = Producto::whereIn('id', array_keys($producto_counts))->get();

            foreach ($productos as $producto) {
                $productos_data[] = [
                    'name' => $producto->name,
                    'price' => $producto->price,
                    'quantity' => $producto_counts[$producto->id],
                ];
            }
        }
        return view('show.cotizacion', [
            'cotizacion' => $cotizacion,
            'servicios_data' => $servicios_data,
            'productos_data' => $productos_data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->update($request->all());

        return response(['data' => $cotizacion, 'mensaje' => 'Se modificó la cotización con exito'], 200);
    }

    public function destroy($id)
    {
        $cotizacion = Cotizacion::findOrFail($id);
        $cotizacion->delete();

        return response(['data' => $cotizacion, 'mensaje' => 'Se eliminó la cotización con exito'], 200);
    }
}
