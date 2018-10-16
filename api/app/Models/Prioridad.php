<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prioridad extends Model
{
    protected $table = 'prioridad';
    protected $primaryKey = 'idprioridad';
    public $timestamps = false;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
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
        return $this->hasMany(Informe::class, 'idinforme');
    }
}
