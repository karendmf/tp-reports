<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Area;
use Illuminate\Http\Request;
use Auth;

class AreaController extends Controller
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
        $area = area::with('user')->get();
        return response()->json($area);
    }
    public function getID(){
        $persona = Auth::user()->idpersona;
        $id = Area::select('idarea')->where('idpersona', $persona)->get();
        return response()->json($id);
    }
    public function show($id)
    {
        $area = Area::find($id);
        $area->user;
        return response()->json($area);
    }
    public function create(Request $request)
    {
        $area = new Area;
        $area->nombre = $request->nombre;
        $area->idpersona = $request->idpersona;

        $area->save();
        return response()->json($area, 201);
    }
    public function update(Request $request, $id)
    {
        $area = Area::find($id);
        $area->idcargo = $request->input('idcargo');
        $area->save();
        return response()->json($area, 200);
    }
    public function delete($id)
    {
        $area = Area::find($id);
        $area->delete();
        return response()->json('Eliminado', 200);
    }

}
