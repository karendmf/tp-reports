<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjuntoinforme extends Model
{
    protected $table = 'adjunto_informe';
    protected $primaryKey = 'idadjunto';
    public $timestamps = false;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'idadjunto', 'idinforme', 'url'
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

}
