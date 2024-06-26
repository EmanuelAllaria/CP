<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Invoice;
use App\Models\Producto;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class InvoiceController extends Controller
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

        $invoices = Invoice::select('invoices.*', 'contacts.name as client_name', 'contacts.company as client_company')
            ->join('contacts', 'invoices.client_id', '=', 'contacts.id')
            ->where('invoices.user_id', $user_id)
            ->orderByDesc('invoices.id')
            ->get();

        return view('invoices', ['invoices' => $invoices, 'user_id' => $user_id]);
    }

    public function store(Request $request)
    {
        $invoice = Invoice::create($request->all());

        return response(['data' => $invoice, 'mensaje' => 'Se creó la factura con exito'], 200);
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);

        $contact = Contact::where('id', $invoice->client_id)->first();
        $invoice->client_name = $contact->name;
        $invoice->client_company = $contact->company;

        $productos_data = [];
        $servicios_data = [];

        if (isset($invoice->servicio_ids)) {
            $servicio_ids = explode(', ', $invoice->servicio_ids);

            $servicio_counts = array_count_values($servicio_ids);

            $servicios = Service::whereIn('id', array_keys($servicio_counts))->get();

            foreach ($servicios as $servicio) {
                $servicios_data[] = [
                    'name' => $servicio->name,
                    'price' => $servicio->price,
                    'quantity' => $servicio_counts[$servicio->id],
                ];
            }
        } elseif (isset($invoice->producto_ids)) {
            $producto_ids = explode(', ', $invoice->producto_ids);

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

        return view('show.invoice', [
            'invoice' => $invoice,
            'servicios_data' => $servicios_data,
            'productos_data' => $productos_data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update($request->all());

        return response(['data' => $invoice, 'mensaje' => 'Se modificó la factura con exito'], 200);
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        $invoices = Invoice::all();

        return view('invoices', ['invoices' => $invoices]);
    }
}
