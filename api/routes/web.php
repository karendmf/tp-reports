<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.

 */

$router->get('/', function () use ($router) {
    return $router->app->version();
});



$router->post('login', 'AuthController@login');
$router->post('solicitud/new', 'SolicitudController@create');

//Persona:
$router->group(['prefix' => 'user', 'middleware' => 'jwt-auth'], function () use ($router) {
    $router->get('ver', 'AuthController@index');
    $router->get('ver/{id}', 'AuthController@show');
    $router->post('mail', 'AuthController@enviarMail');
    $router->post('register', 'AuthController@register');
});

//HSEQ:
$router->group(['prefix' => 'hseq', 'middleware' => 'jwt-auth'], function () use ($router) {
    $router->get('ver', 'HseqController@index');
    $router->get('ver/{id}', 'HseqController@show');
    $router->post('new', 'HseqController@nuevo');
    $router->put('update/{id}', 'HseqController@update');
    $router->delete('delete/{id}', 'HseqController@delete');
    $router->get('search', 'HseqController@search');
    $router->get('id', 'HseqController@getID');

});

//AREA:
$router->group(['prefix' => 'area', 'middleware' => 'jwt-auth'], function () use ($router) {
    $router->get('ver', 'AreaController@index');
    $router->get('ver/{id}', 'AreaController@show');
    $router->post('new', 'AreaController@create');
    $router->put('update/{id}', 'AreaController@update');
    $router->delete('delete/{id}', 'AreaController@delete');
    $router->get('search', 'AreaController@search');
    $router->get('id', 'AreaController@getID');

});

//Solicitudes
$router->group(['prefix' => 'solicitudes', 'middleware' => 'jwt-auth'], function () use ($router) {
    $router->get('ver', 'SolicitudController@index');
    $router->delete('delete/{id}', 'SolicitudController@delete');
});

//Cargo:
$router->group(['prefix' => 'cargo', 'middleware' => 'jwt-auth'], function () use ($router) {
    $router->get('ver', 'CargoController@index');
    $router->get('ver/{id}', 'CargoController@show');
    $router->post('new', 'CargoController@create');
    $router->put('update/{id}', 'CargoController@update');
    $router->delete('delete/{id}', 'CargoController@delete');
});


//Informe:
$router->group(['prefix' => 'informe', 'middleware' => 'jwt-auth'], function () use ($router) {
    $router->get('ver', 'InformeController@index');
    $router->get('ver/{id}', 'InformeController@show');
    $router->post('new', 'InformeController@create');
    $router->put('update/{id}', 'InformeController@update');
    $router->delete('delete/{id}', 'InformeController@delete');
    $router->get('me/{idhseq}', 'InformeController@informesHseq');
    $router->get('cerrar/{id}', 'InformeController@cerrar');
    $router->get('cantInformes/{idhseq}', 'InformeController@cantidadInformesPropios');
    $router->get('cantPrioridad/{idhseq}', 'InformeController@cantidadPrioridad');
    $router->get('cantMesCreate/{idhseq}', 'InformeController@cantidadPorMesCreate');
    $router->post('mail', 'InformeController@enviarMail');
});

//Imagenes de informe:
$router->group(['prefix' => 'informe/img', 'middleware' => 'jwt-auth'], function () use ($router) {
    $router->get('ver', 'AdjuntoinformeController@index');
    $router->get('ver/{id}', 'AdjuntoinformeController@show');
    $router->post('new', 'AdjuntoinformeController@create');
    $router->put('update/{id}', 'AdjuntoinformeController@update');
    $router->delete('delete/{id}', 'AdjuntoinformeController@delete');
    
});

//Tarea:
$router->group(['prefix' => 'tarea', 'middleware' => 'jwt-auth'], function () use ($router) {
    $router->get('ver', 'TareaController@index');
    $router->get('ver/{id}', 'TareaController@show');
    $router->post('new', 'TareaController@create');
    $router->put('update/{id}', 'TareaController@update');
    $router->delete('delete/{id}', 'TareaController@delete');
    $router->get('me/{idarea}', 'TareaController@tareasArea');
    $router->get('estado/{idArea}', 'TareaController@estadisticaEstado');
});

//Estado
$router->group(['prefix' => 'estado', 'middleware' => 'jwt-auth'], function () use ($router) {
    $router->get('ver', 'EstadoController@index');
    $router->get('ver/{id}', 'EstadoController@show');
    $router->post('new', 'EstadoController@create');
    $router->put('update/{id}', 'EstadoController@update');
    $router->delete('delete/{id}', 'EstadoController@delete');

});
//Prioridad
$router->group(['prefix' => 'prioridad', 'middleware' => 'jwt-auth'], function () use ($router) {
    $router->get('ver', 'PrioridadController@index');
    $router->get('ver/{id}', 'PrioridadController@show');
    $router->post('new', 'PrioridadController@create');
    $router->put('update/{id}', 'PrioridadController@update');
    $router->delete('delete/{id}', 'PrioridadController@delete');

});
//Zona
$router->group(['prefix' => 'zona', 'middleware' => 'jwt-auth'], function () use ($router) {
    $router->get('ver', 'ZonaController@index');
    $router->get('ver/{id}', 'ZonaController@show');
    $router->post('new', 'ZonaController@create');
    $router->put('update/{id}', 'ZonaController@update');
    $router->delete('delete/{id}', 'ZonaController@delete');

});
//Tarea Detalle:
$router->group(['prefix' => 'tareadetalle', 'middleware' => 'jwt-auth'], function () use ($router) {
    $router->get('ver', 'TareadetalleController@index');
    $router->get('ver/{id}', 'TareadetalleController@show');
    $router->post('new', 'TareadetalleController@create');
    $router->put('update/{id}', 'TareadetalleController@update');
    $router->delete('delete/{id}', 'TareadetalleController@delete');
});

//Progreso
$router->group(['prefix' => 'tarea/progreso', 'middleware' => 'jwt-auth'], function () use ($router) {
    $router->get('ver/{id}', 'ProgresoTareaController@show');
    $router->get('verPorTarea/{id}', 'ProgresoTareaController@verPorTarea');
    $router->post('new', 'ProgresoTareaController@create');
    $router->put('update/{id}', 'ProgresoTareaController@update');
    $router->delete('delete/{id}', 'ProgresoTareaController@delete');

});

//Modifica Informe:
$router->group(['prefix' => 'informe/modifica', 'middleware' => 'jwt-auth'], function () use ($router) {
    $router->get('ver', 'ModificainformeController@index');
    $router->get('ver/{id}', 'ModificainformeController@show');
    $router->get('historial/{idinforme}', 'ModificainformeController@modInforme');
    $router->post('new', 'ModificainformeController@create');
});

//Cambio estado informe:
    $router->group(['prefix' => 'informe/estado', 'middleware' => 'jwt-auth'], function () use ($router) {
        $router->get('ver', 'CambioestadoController@index');
        $router->get('ver/{id}', 'CambioestadoController@show');
        $router->get('quien/{idinforme}', 'CambioestadoController@quienCerro');
        $router->post('new', 'CambioestadoController@create');
    });