<?php

namespace App\Http\Controllers;

use App\Models\CambioEstado;
use Illuminate\Http\Request;

class CambioestadoController extends Controller
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
        $cambio = CambioEstado::all();
        return response()->json($cambio);
    }
    public function show($id)
    {
        $cambio = CambioEstado::find($id);
        return response()->json($cambio);
    }
    public function create(Request $request)
    {
        $cambio = new CambioEstado;
        $cambio->idinforme = $request->input('idinforme');
        $cambio->idhseq = $request->input('idhseq');
        $cambio->idestado = $request->input('idestado');

        $cambio->save();
        return response()->json($cambio, 201);
    }
    public function quienCerro($idinforme){
        if($cambio = CambioEstado::where('idinforme', $idinforme)->orderBy('idcambio', 'desc')->first()){
            $cambio->hseq->user;
            return response()->json($cambio);
        }else{
            return response()->json(0);
        }
    }

}
