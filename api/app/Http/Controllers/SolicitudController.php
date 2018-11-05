<?php

namespace App\Http\Controllers;
use App\Models\Solicitud;
use Illuminate\Http\Request;

class SolicitudController extends Controller
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
        $solicitud = Solicitud::all();
        return response()->json($solicitud);
    }

    public function create(Request $request)
    {
        if ($request->has('legajo')){
            $solicitud = new Solicitud;
            $solicitud->nombre = $request->input('nombre');
            $solicitud->apellido = $request->input('apellido');
            $solicitud->legajo = $request->input('legajo');
            $solicitud->email = $request->input('email');

            if (Solicitud::where('legajo', '=', $request->input('legajo'))->exists()) {
                return response()->json("¡Usted ya ha solicitado un Usuario!", 409);
            } else {
                if ($solicitud->save()) {
                    return response()->json("Se ha realizado la solicitud", 201);
                } else {
                    return response()->json("Error al solicitar usuario.", 400);
                }
            }
        } else {
            return response()->json("Falta información para solicitar un usuario!", 422);
        }
    }
    
    public function delete($id)
    {
        $solicitud = Solicitud::find($id);
        $solicitud->delete();
        return response()->json('Solicitud eliminada', 200);
    }
}
