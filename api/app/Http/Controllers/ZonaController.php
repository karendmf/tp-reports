<?php

namespace App\Http\Controllers;

use App\Models\Zona;
use Illuminate\Http\Request;

class ZonaController extends Controller
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
        $zona = Zona::all();
        return response()->json($zona);
    }
    public function show($id)
    {
        $zona = Zona::find($id);
        return response()->json($zona);
    }
    public function create(Request $request)
    {
        $zona = new Zona;
        $zona->nombre = $request->nombre;

        $zona->save();
        return response()->json($zona, 201);
    }
    public function update(Request $request, $id)
    {
        $zona = Zona::find($id);
        $zona->nombre = $request->input('nombre');
        $zona->save();
        return response()->json($zona, 200);
    }
    public function delete($id)
    {
        $zona = Zona::find($id);
        $zona->delete();
        return response()->json('Eliminado', 200);
    }

}
