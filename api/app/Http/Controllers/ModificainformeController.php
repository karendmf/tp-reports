<?php

namespace App\Http\Controllers;

use App\Models\ModificaInforme;
use Illuminate\Http\Request;

class ModificainformeController extends Controller
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
        $modifica = ModificaInforme::all();
        return response()->json($modifica);
    }
    public function show($id)
    {
        $modifica = ModificaInforme::find($id);
        return response()->json($modifica);
    }
    public function create(Request $request)
    {
        $modifica = new ModificaInforme;
        $modifica->idinforme = $request->input('idinforme');
        $modifica->idhseq = $request->input('idhseq');

        $modifica->save();
        return response()->json($modifica, 201);
    }
    public function modInforme($idinforme){
        if($modifica = ModificaInforme::where('idinforme', $idinforme)->orderBy('idmodifica', 'desc')->first()){
            $modifica->hseq->user;
            return response()->json($modifica);
        }else{
            return response()->json(0);
        }
    }

}
