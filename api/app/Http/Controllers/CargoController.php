<?php

namespace App\Http\Controllers;
use App\Models\Cargo;

class CargoController extends Controller
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
        $cargo = Cargo::all();
        return response()->json($cargo);
    }

    public function show($id)
    {
        $cargo = Cargo::find($id);
        return response()->json($cargo);
    }
    public function create(Request $request)
    {
        $cargo = new Cargo;
        $cargo->nombre = $request->nombre;

        $cargo->save();
        return response()->json($cargo, 201);
    }
    public function update(Request $request, $id)
    {
        $cargo = Cargo::find($id);
        $cargo->nombre = $request->input('nombre');
        $cargo->save();
        return response()->json($cargo, 200);
    }
    public function delete($id)
    {
        $cargo = Cargo::find($id);
        $cargo->delete();
        return response()->json('Cargo eliminado', 200);
    }
}
