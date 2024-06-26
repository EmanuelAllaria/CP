<?php

namespace App\Http\Controllers;

use App\Imports\ProductosImport;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
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
                Contact::create($item);
                $insert++;
            }

            return response()->json(['success' => 'Imported ' . $insert . ' rows successfully.', 'data' => $data], 200);
        } catch (\Throwable $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Error importing Excel file: ' . $e->getMessage()], 500);
            } else {
                return back()->with('error', 'Error importing Excel file: ' . $e->getMessage());
            }
        }
    }
}
