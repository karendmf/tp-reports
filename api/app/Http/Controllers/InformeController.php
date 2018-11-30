<?php

namespace App\Http\Controllers;

use App\Models\Informe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $losInformes=[];
        $i=0;
        //$informes->each->hseq;
        foreach ($informes as $informe) {
            $usuario= $informe->hseq->user;
            $losInformes[$i]['usuario']= $usuario;
            $losInformes[$i]['informe']=$informe;
            $i++;
        }
        return response()->json($losInformes);
        
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
            $informe->tarea->each->detalle;
            $informe->tarea->each->progreso;
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
        $informe->idzona = $request->input('idzona');
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
        $informes = Informe::with('hseq','zona', 'estado', 'prioridad', 'adjunto', 'tarea')->where('idhseq', $idhseq)->orderBy('fechalimite', 'ASC')->get();
        return response()->json($informes);
    }
    public function cerrar($id){
        $informe = Informe::find($id);
        $informe->idestado = 2;
        $informe->save();
        return response()->json('Informe cerrado', 200);
    }
    public function cantidadInformesPropios($idhseq){

        $cerrado = Informe::where('idhseq', $idhseq)->where('idestado',2)->count();
        $abierto = Informe::where('idhseq', $idhseq)->where('idestado',1)->count();
        $vencido = Informe::where('idhseq', $idhseq)->where('idestado',1)->whereDate('fechalimite', '<', date('Y-m-d'))->count();
        $total = Informe::where('idhseq', $idhseq)->count();
        $cantidad['cerrado'] = $cerrado;
        $cantidad['abierto'] = $abierto;
        $cantidad['vencido'] = $vencido;

        return response()->json($cantidad);
    }
    public function cantidadPrioridad($idhseq){
        $cantidad= DB::table('informe as i')
        ->join('prioridad as p', 'i.idprioridad', '=', 'p.idprioridad')
        ->select(DB::raw('count(i.idprioridad) as cantidad, p.nombre as nombre'))
        ->where('i.idhseq', '=', $idhseq)
        ->groupBy('i.idprioridad')
        ->get();
        return response()->json($cantidad);
    }
    public function cantidadPorMesCreate($idhseq){
        $cantidad = DB::table('informe as i')
        ->select(DB::raw('count(MONTH(i.create_at)) as cantidad, MONTH(i.create_at) as mes'))
        ->groupBy(DB::raw('MONTH(i.create_at)'))
        ->where('idhseq', $idhseq)
        ->get();
        return response()->json($cantidad);
    }
}
