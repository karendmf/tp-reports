<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CambioEstado extends Model
{
    protected $table = 'cambio_estado';
    protected $primaryKey = 'idcambio';
    public $timestamps = false;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'idinforme', 'idhseq', 'fecha_hora', 'idestado'
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
    public function estado(){
        return $this->belongsTo(Estado::class, 'idestado');
    }
    public function hseq(){
        return $this->belongsTo(Hseq::class, 'idhseq');
    }
}
