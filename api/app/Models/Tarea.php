<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $table = 'tarea';
    protected $primaryKey = 'idtarea';
    public $timestamps = false;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'idinforme','titulo','descripcion','idarea'
    ];

    /**
     * Los atributos excluidos del formulario JSON del modelo.
     *
     * @var array
     */
    /* protected $hidden = [

];
    */
    public function informe(){
        return $this->belongsTo(Informe::class, 'idinforme');
    }
    public function area(){
        return $this->belongsTo(Area::class, 'idarea');
    }
    public function detalle(){
        return $this->hasOne(Tareadetalle::class, 'idtarea');
    }

}
