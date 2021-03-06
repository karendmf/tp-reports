<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    public function index()
    {
        $tarea = Tarea::all();
        $tarea->each->area;
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
            $tarea->progreso;
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
        $tarea->idarea = $request->input('idarea');
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
        $tareas = Tarea::where('idarea', $idarea)->get();
        $tareas->each->informe;
        $tareas->each->area;
        return response()->json($tareas);
    }
    
    public function estadisticaEstado($idarea){
        $datos = DB::select('SELECT count(d.idtarea_detalle) as cerradas, count(t.idtarea)-count(d.idtarea_detalle) as abiertas FROM tarea t left JOIN tarea_detalle d ON t.idtarea=d.idtarea WHERE idarea='.$idarea);
        return response()->json($datos);
        
    }

}