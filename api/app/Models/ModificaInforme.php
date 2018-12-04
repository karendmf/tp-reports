<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModificaInforme extends Model
{
    protected $table = 'modifica';
    protected $primaryKey = 'idmodifica';
    public $timestamps = false;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'idinforme', 'idhseq', 'fecha_hora'
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
    public function hseq(){
        return $this->belongsTo(Hseq::class, 'idhseq');
    }
}
