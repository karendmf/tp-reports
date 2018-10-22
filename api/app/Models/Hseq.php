<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hseq extends Model
{
    protected $table = 'hseq';
    protected $primaryKey = 'idhseq';
    public $timestamps = false;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'idcargo','idpersona'
    ];

    /**
     * Una persona tiene un cargo
     * Obtener el cargo de la persona
     * @return BelongTo
     */
    public function cargo(){
        return $this->belongsTo(Cargo::class, 'idcargo');
    }

    public function user(){
        return $this->belongsTo(User::class, 'idpersona');
    }
    public function informe(){
        return $this->hasMany(Informe::class, 'idinforme');
    }

}
