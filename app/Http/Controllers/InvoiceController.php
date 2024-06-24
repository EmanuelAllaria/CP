<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Invoice;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = DB::table('invoices')
            ->select('invoices.*', 'contacts.name as client_name', 'contacts.company as client_company')
            ->join('contacts', 'invoices.client_id', '=', 'contacts.id')
            ->orderByDesc('invoices.id')
            ->get();

        return view('invoices', ['invoices' => $invoices]);
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

        $servicio_ids = explode(', ', $invoice->servicio_ids);

        $servicio_counts = array_count_values($servicio_ids);

        $servicios = Service::whereIn('id', array_keys($servicio_counts))->get();

        $servicios_data = [];
        foreach ($servicios as $servicio) {
            $servicios_data[] = [
                'name' => $servicio->name,
                'price' => $servicio->price,
                'quantity' => $servicio_counts[$servicio->id],
            ];
        }

        return view('show.invoice', [
            'invoice' => $invoice,
            'servicios_data' => $servicios_data,
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
