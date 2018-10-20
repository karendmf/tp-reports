<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Hseq;
use Illuminate\Support\Facades\Request;
use Auth;

class HseqController extends Controller
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
        $hseq = Hseq::with('cargo','user')->get();
        return response()->json($hseq);
    }
    public function getID(){
        $persona = Auth::user()->idpersona;
        $id = Hseq::select('idhseq')->where('idpersona', $persona)->get();
        return response()->json($id);
    }
    public function show($id)
    {
        $hseq = Hseq::find($id);
        $hseq->cargo; //<-- IMPORTANTE
        $hseq->user;
        return response()->json($hseq);
    }
    public function create(Request $request)
    {
        $hseq = new Hseq;
        $hseq->idcargo = $request->idcargo;
        $hseq->idpersona = $request->idpersona;

        $hseq->save();
        return response()->json($hseq, 201);
    }
    public function update(Request $request, $id)
    {
        $hseq = Hseq::find($id);
        $hseq->idcargo = $request->input('idcargo');
        $hseq->save();
        return response()->json($hseq, 200);
    }
    public function delete($id)
    {
        $hseq = Hseq::find($id);
        $hseq->delete();
        return response()->json('Eliminado', 200);
    }

}
