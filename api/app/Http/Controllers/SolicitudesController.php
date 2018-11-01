<?php

namespace App\Http\Controllers;
use App\Models\Solicitudes;
use Illuminate\Http\Request;

class SolicitudesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     //
    // }

    public function index()
    {
        $solicitud = Solicitudes::all();
        return response()->json($solicitud);
    }

    public function create(Request $request)
    {
        $solicitud = new Solicitudes;
        $solicitud->nombre = $request->input('nombre');
        $solicitud->apellido = $request->input('apellido');
        $solicitud->legajo = $request->input('legajo');
        $solicitud->email = $request->input('email');

        $solicitud->save();
        return response()->json($solicitud, 201);
    }
    
    public function delete($id)
    {
        $solicitud = Solicitudes::find($id);
        $solicitud->delete();
        return response()->json('Solicitud eliminada', 200);
    }
}
