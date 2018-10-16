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
        $informes = Informe::with('zona', 'estado', 'prioridad', 'adjunto')->get();
        return response()->json($informes);
    }

    public function create(Request $request)
    {
        $informe = new Informe;
        $informe->idhseq = $request->idhseq;
        $informe->idzona = $request->idzona;
        $informe->idprioridad = $request->idprioridad;
        $informe->idestado = $request->idestado;
        $informe->titulo = $request->titulo;
        $informe->descripcion = $request->descripcion;
        $informe->fechalimite = $request->fechalimite;

        $informe->save();
        return response()->json($informe, 201);
    }

    public function show($id)
    {
        $informe = Informe::find($id);
        $informe->estado;
        $informe->prioridad;
        $informe->zona;
        $informe->adjunto;
        return response()->json($informe);
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
}
