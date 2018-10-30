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

        if ($request->hasFile('file')) {
            $adjuntoinforme = new Adjuntoinforme;
            $url = 'img/ad_inf/';
            $image = $request->file('file');
            $ext = $image->extension();
            $nombre = time() . '_' . rand(10, 15) . '.' . $ext;
            $adjuntoinforme->idinforme = $request->idinforme;
            $adjuntoinforme->url = $url . $nombre;

            if ($adjuntoinforme->save()) {
                $image->move($url, $nombre);
                return response()->json($adjuntoinforme, 201);
            
            } else {
                return response()->json('No se cargo nada', 200);
            }
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
