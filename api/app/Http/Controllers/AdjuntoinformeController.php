<?php

namespace App\Http\Controllers;

use App\Models\Adjuntoinforme;
use Illuminate\Http\Request;

class AdjuntoinformeController extends Controller
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
        $adjuntoinforme = Adjuntoinforme::all();
        return response()->json($adjuntoinforme);
    }

    public function create(Request $request)
    {
        $adjuntoinforme = new Adjuntoinforme;
        if ($request->hasFile('image')){
            $url = 'img/ad_inf/';
            $image = $request->file('image');
            $ext = $image->extension();
            $nombre = time().'_'.rand(10, 300).'.'.$ext;
            $adjuntoinforme->idinforme = $request->idinforme;
            $adjuntoinforme->url = $url.$nombre;
            $adjuntoinforme->descripcion = $request->descripcion;

            $adjuntoinforme->save();
            $request->file('image')->move($url, $nombre);
            return response()->json($adjuntoinforme, 201);
        }
    }

    public function show($id)
    {
        $adjuntoinforme = Adjuntoinforme::find($id);
        return response()->json($adjuntoinforme);
    }

    public function update(Request $request, $id)
    {
        $adjuntoinforme = Adjuntoinforme::find($id);
        
        $adjuntoinforme->idinforme = $request->input('idinforme');
        $adjuntoinforme->url = $request->input('url');
        $adjuntoinforme->descripcion = $request->input('descripcion');

        $adjuntoinforme->save();
        return response()->json($adjuntoinforme, 200);
    }

    public function delete($id)
    {
        $adjuntoinforme = Adjuntoinforme::find($id);
        $adjuntoinforme->delete();
        return response()->json('Informe eliminado', 200);
    }


}
