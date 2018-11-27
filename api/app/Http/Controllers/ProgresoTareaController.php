<?php

namespace App\Http\Controllers;

use App\Models\ProgresoTarea;
use Illuminate\Http\Request;

class ProgresoTareaController extends Controller
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

    public function show($id)
    {
        $progresoTarea = ProgresoTarea::find($id);
        return response()->json($progresoTarea);
    }
    public function create(Request $request)
    {
        $progresoTarea = new ProgresoTarea;
        $progresoTarea->porcentaje = $request->input('porcentaje');
        $progresoTarea->idtarea = $request->input('idtarea');
        $progresoTarea->save();
        return response()->json($progresoTarea, 201);
    }
    public function update(Request $request, $id)
    {
        $progresoTarea = ProgresoTarea::find($id);
        $progresoTarea->porcentaje = $request->input('porcentaje');
        $progresoTarea->save();
        return response()->json($progresoTarea, 200);
    }
    public function delete($id)
    {
        $progresoTarea = ProgresoTarea::find($id);
        $progresoTarea->delete();
        return response()->json('Eliminado', 200);
    }
    public function verPorTarea($idtarea)
    {
        $progresoTarea = ProgresoTarea::where('idtarea', $idtarea)->get();
        return response()->json($progresoTarea);
    }

}
