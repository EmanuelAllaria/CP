<?php

namespace App\Http\Controllers;

use App\Imports\ProductosImport;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return response()->json(['mensaje' => 'Hubo un error, inicie sesion nuevamente porfavor.'], 404);
        }
        $user_id = auth()->id();

        $contacts = Contact::where('user_id', $user_id)->orderBy('id', 'asc')->get();
        return view('contacts', ['contacts' => $contacts, 'user_id' => $user_id]);
    }

    public function store(Request $request)
    {
        $contact = new Contact;
        $contact->name = $request->name;
        $contact->company = $request->company;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->user_id = $request->user_id;
        $contact->active = 1;
        $contact->save();

        return response(['data' => $contact, 'mensaje' => 'Se creó el cliente con exito'], 200);
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
                $item['active'] = 1;
                dd($item);
                Contact::create($item);
                $insert++;
            }

            return response()->json(['success' => 'Imported ' . $insert . ' rows successfully.', 'data' => $data], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Error importing Excel file: ' . $e->getMessage()], 500);
        }
    }
}
