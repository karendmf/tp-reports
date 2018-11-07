<?php

namespace App\Http\Controllers;

use App\Models\Tareadetalle;
use Illuminate\Http\Request;

class TareadetalleController extends Controller
{
    public function index()
    {
        $tareaDetalle = Tareadetalle::with('tarea')->get();
        return response()->json($tareaDetalle);
    }

    public function show($id)
    {
        if($tareaDetalle = Tareadetalle::find($id)){
            $tareaDetalle->tarea;
            return response()->json($tareaDetalle);
        }else{
            return response()->json('La tarea no existe.', 404);
        }
        
    }
    public function create(Request $request)
    {
        $tareaDetalle = new Tareadetalle;
        $tareaDetalle->descripcion = $request->input('descripcion');
        $tareaDetalle->idtarea =  $request->input('idtarea');

        if($tareaDetalle->save()){
            return response()->json($tareaDetalle, 201);
        }else{
            return response()->json(400);
        }
    }
    public function update(Request $request, $id)
    {
        $tareaDetalle = Tareadetalle::find($id);
        $tareaDetalle->descripcion = $request->input('descripcion');
        $tareaDetalle->save();
        return response()->json($tareaDetalle, 200);
    }
    public function delete($id)
    {
        $tareaDetalle = Tareadetalle::find($id);
        $tareaDetalle->delete();
        return response()->json('tarea eliminada', 200);
    }
}