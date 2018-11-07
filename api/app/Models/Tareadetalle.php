<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tareadetalle extends Model
{
    protected $table = 'tarea_detalle';
    protected $primaryKey = 'idtarea_detalle';
    public $timestamps = false;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'idtarea_detalle','descripcion','idtarea', 'fecha_hora'
    ];

    /**
     * Los atributos excluidos del formulario JSON del modelo.
     *
     * @var array
     */
    /* protected $hidden = [

];
    */

    public function tarea(){
        return $this->belongsTo(Tarea::class, 'idtarea');
    }

}
