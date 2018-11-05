<?php

namespace App\Http\Controllers;

use App\Models\Informe;
use Illuminate\Http\Request;

class InformeController extends Controller
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
        $informes = Informe::with('zona', 'estado', 'prioridad', 'adjunto','tarea')->get();
        return response()->json($informes);
    }

    public function create(Request $request)
    {
        
        $informe = new Informe;
        $informe->idhseq = $request->input('idhseq');
        $informe->idzona = $request->input('idzona');
        $informe->idprioridad = $request->input('idprioridad');
        $informe->idestado = $request->input('idestado');
        $informe->titulo = $request->input('titulo');
        $informe->descripcion = $request->input('descripcion');
        $informe->fechalimite = $request->input('fechalimite');

        $informe->save();
        return response()->json($informe, 201);
    }

    public function show($id)
    {
        if($informe = Informe::find($id)){
            $informe->estado;
            $informe->prioridad;
            $informe->zona;
            $informe->adjunto;
            $informe->hseq->user;
            $informe->tarea->each->area;
            return response()->json($informe);
        }else{
            return response()->json('El informe no existe', 404);
        }
    }

    public function update(Request $request, $id)
    {
        $informe = Informe::find($id);
        $informe->titulo = $request->input('titulo');
        $informe->fechalimite = $request->input('fechalimite');
        $informe->descripcion = $request->input('descripcion');
        $informe->idprioridad = $request->input('idprioridad');
        $informe->idestado = $request->input('idestado');
        $informe->save();
        return response()->json($informe, 200);
    }

    public function delete($id)
    {
        $informe = Informe::find($id);
        $informe->delete();
        return response()->json('Informe eliminado', 200);
    }
    public function informesHseq($idhseq){
        $informes = Informe::with('hseq','zona', 'estado', 'prioridad', 'adjunto')->where('idhseq', $idhseq)->get();
        return response()->json($informes);
    }
}
