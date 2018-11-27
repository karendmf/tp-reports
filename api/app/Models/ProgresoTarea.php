<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgresoTarea extends Model
{
    protected $table = 'avance';
    protected $primaryKey = 'idavance';
    public $timestamps = false;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'porcentaje', 'idtarea'
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
