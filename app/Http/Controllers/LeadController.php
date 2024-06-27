<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Contact;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function gestionarLeads()
    {
        if (!auth()->check()) {
            return response()->json(['mensaje' => 'Hubo un error, inicie sesion nuevamente porfavor.'], 404);
        }
        $user_id = auth()->id();

        $leads = Lead::where('user_id', $user_id)->get();
        return view('leads', ['leads' => $leads, 'user_id' => $user_id]);
    }

    public function crearLead(Request $request)
    {
        $lead = $request->all();
        $lead['monto'] = floatval($lead['monto']);
        if (
            $request->cliente_id && $request->cliente_id !== '' &&
            (!$request->nombre || $request->nombre === '') &&
            (!$request->email || $request->email === '') &&
            (!$request->telefono || $request->telefono === '')
        ) {
            $cliente = Contact::where('id', $request->cliente_id)->first();

            if ($cliente) {
                $lead['nombre'] = $cliente->name;
                $lead['email'] = $cliente->email;
                $lead['telefono'] = $cliente->phone;
            }
        } else {
            $cliente = Contact::create([
                'user_id' => $lead['user_id'],
                'name' => $lead['nombre'],
                'email' => $lead['email'],
                'phone' => $lead['telefono'],
                'activo' => 0,
            ]);

            if ($cliente) {
                $lead['cliente_id'] = $cliente->id;
            }
        }

        // Create lead using Lead model
        $leadCreate = Lead::create($lead);
        $ventaCreate = Venta::create([
            'user_id' => $lead['user_id'],
            'cliente_id' => $lead['cliente_id'],
            'producto_id' => $lead['producto_id'],
            'cantidad' => $lead['cantidad'],
            'monto' => $lead['monto'],
        ]);
        $invoiceCreate = Invoice::create([
            'user_id' => $lead['user_id'],
            'producto_ids' => implode(', ', array_fill(0, intval($lead['cantidad']), $lead['producto_id'])),
            'client_id' => $lead['cliente_id'],
            'amount' => $lead['monto'],
            'status' => 'paid'
        ]);

        return response()->json(['data' => ['load' => $leadCreate, 'venta' => $ventaCreate, 'factura' => $invoiceCreate], 'mensaje' => 'Se creó el lead con éxito'], 200);
    }


    public function convertirLead(Request $request, Lead $lead)
    {
        $clienteExiste = Contact::find($lead['cliente_id']);
        if (isset($clienteExiste->id)) {
            $clienteExiste->update(['active' => 1]);
        }

        $lead->update(['estado' => 'convertido']);

        $leads = Lead::all();
        return view('leads', compact('leads'));
    }

    public function automatizarVenta(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
        ]);

        $lead = Lead::find($validated['lead_id']);

        // Crear cliente si no existe
        $cliente = Contact::firstOrCreate([
            'name' => $lead->nombre,
            'email' => $lead->email,
            'phone' => $lead->telefono,
        ]);

        // Crear la venta
        $venta = Venta::create([
            'cliente_id' => $cliente->id,
            'producto_id' => $lead->producto_id, // Asegúrate de tener el producto_id disponible en el lead
            'cantidad' => $lead->cantidad,
        ]);

        $lead->update(['estado' => 'convertido']);

        return view('show.venta', ['venta' => $venta]);
    }
}
