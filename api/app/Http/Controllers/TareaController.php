<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    public function index()
    {
        $tarea = Tarea::with('area')->get();
        return response()->json($tarea);
    }

    public function show($id)
    {
        if($tarea = Tarea::find($id)){
            $tarea->area;
            $tarea->detalle;
            $tarea->informe->zona;
            $tarea->informe->prioridad;
            $tarea->informe->adjunto;
            return response()->json($tarea);
        }else{
            return response()->json('La tarea no existe', 404);
        }
    }
    public function create(Request $request)
    {
        $tarea = new Tarea;
        $tarea->idarea = $request->idarea;
        $tarea->idinforme = $request->idinforme;
        $tarea->titulo = $request->titulo;
        $tarea->descripcion = $request->descripcion;

        if($tarea->save()){
            return response()->json($tarea, 201);
        }else{
            return response()->json(400);
        }
    }
    public function update(Request $request, $id)
    {
        $tarea = Tarea::find($id);
        $tarea->titulo = $request->input('titulo');
        $tarea->descripcion = $request->input('descripcion');
        $tarea->save();
        return response()->json($tarea, 200);
    }
    public function delete($id)
    {
        $tarea = Tarea::find($id);
        $tarea->delete();
        return response()->json('tarea eliminada', 200);
    }
    public function tareasArea($idarea){
        $tareas = Tarea::with('informe')->where('idarea', $idarea)->get();
        return response()->json($tareas);
    }
}