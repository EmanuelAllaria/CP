<?php

namespace App\Http\Controllers;

use App\Imports\ProductosImport;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class ProductoController extends Controller
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

        $productos = Producto::where('user_id', $user_id)->orderBy('id', 'asc')->get();
        return view('productos', ['productos' => $productos, 'user_id' => $user_id]);
    }

    public function store(Request $request)
    {
        $producto = Producto::create($request->all());
        return response()->json(['data' => $producto, 'mensaje' => 'Se creó el producto con éxito'], 200);
    }

    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return view('show.producto', compact('producto'));
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->update($request->all());
        return response()->json(['data' => $producto, 'mensaje' => 'Se modificó el producto con éxito'], 200);
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        return response()->json(['data' => $producto, 'mensaje' => 'Se eliminó el producto con éxito'], 204);
    }

    public function uploadExcel(Request $request)
    {
        $file = $request->file('excelFile');

        try {
            $import = new ProductosImport();
            Excel::import($import, $file);

            $data = $import->getData();
            $insert = 0;

            foreach ($data as $item) {
                $item['user_id'] = intval($request->user_id);
                Producto::create($item);
                $insert++;
            }

            return response()->json(['success' => 'Imported ' . $insert . ' rows successfully.', 'data' => $data], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Error importing Excel file: ' . $e->getMessage()], 500);
        }
    }
}
