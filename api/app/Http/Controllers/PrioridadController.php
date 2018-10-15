<?php

namespace App\Http\Controllers;

use App\Models\Prioridad;
use Illuminate\Http\Request;

class PrioridadController extends Controller
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
        $prioridad = Prioridad::all();
        return response()->json($prioridad);
    }

    public function show($id)
    {
        $prioridad = Prioridad::find($id);
        return response()->json($prioridad);
    }
    public function create(Request $request)
    {
        $prioridad = new Prioridad;
        $prioridad->nombre = $request->nombre;

        $prioridad->save();
        return response()->json($prioridad, 201);
    }
    public function update(Request $request, $id)
    {
        $prioridad = Prioridad::find($id);
        $prioridad->nombre = $request->input('nombre');
        $prioridad->save();
        return response()->json($prioridad, 200);
    }
    public function delete($id)
    {
        $prioridad = Prioridad::find($id);
        $prioridad->delete();
        return response()->json('Eliminado', 200);
    }

}
