<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = 'solicitudes';
    protected $primaryKey = 'idsolicitud';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'apellido', 'legajo', 'email'
    ];
    
}
