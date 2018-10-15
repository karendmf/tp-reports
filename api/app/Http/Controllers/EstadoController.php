<?php

namespace App\Http\Controllers;

use App\Models\Estado;

class EstadoController extends Controller
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
        $estados = Estado::all();
        return response()->json($estados);
    }

    public function show($id)
    {
        $estado = Estado::find($id);
        return response()->json($estado);
    }
    public function create(Request $request)
    {
        $estado = new Estado;
        $estado->nombre = $request->nombre;

        $estado->save();
        return response()->json($estado, 201);
    }
    public function update(Request $request, $id)
    {
        $estado = Estado::find($id);
        $estado->nombre = $request->input('nombre');
        $estado->save();
        return response()->json($estado, 200);
    }
    public function delete($id)
    {
        $estado = Estado::find($id);
        $estado->delete();
        return response()->json('Eliminado', 200);
    }

}
