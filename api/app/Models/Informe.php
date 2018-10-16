<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    protected $table = 'informe';
    protected $primaryKey = 'idinforme';
    public $timestamps = false;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'idhseq', 'idzona', 'idprioridad', 'idestado', 'titulo', 'descripcion', 'fechalimite',
    ];

    /**
     * Los atributos excluidos del formulario JSON del modelo.
     *
     * @var array
     */
    /* protected $hidden = [

];
 */
    public function estado(){
        return $this->belongsTo(Estado::class, 'idestado');
    }
    public function prioridad(){
        return $this->belongsTo(Prioridad::class, 'idprioridad');
    }
    public function zona(){
        return $this->belongsTo(Zona::class, 'idzona');
    }
    public function adjunto(){
        return $this->hasMany(Adjuntoinforme::class, 'idadjunto');
    }
}
