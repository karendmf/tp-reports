<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'area';
    protected $primaryKey = 'idarea';
    public $timestamps = false;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'idpersona'
    ];
   
    public function user(){
        return $this->belongsTo(User::class, 'idpersona', 'idpersona');
    }
    /* public function tarea(){
        return $this->hasMany(Tarea::class, 'idtarea');
    } */

}
