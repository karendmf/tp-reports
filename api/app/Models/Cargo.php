<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargo';
    protected $primaryKey = 'idcargo';
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
     * Un cargo lo tienen muchas personas de hseq
     * Obtener las personas con el mismo cargo
     * @return HasMany
     */
    public function hseq(){
        return $this->hasMany(Hseq::class, 'idhseq');
    }



}
