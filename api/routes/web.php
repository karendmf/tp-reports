<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->get('/hola', function () use ($router) {
    return response()->json([
        'message' => 'Holi!',
    ]);
});

//Persona:
$router->group(['prefix' => 'user'], function () use ($router) {
    $router->get('ver', 'AuthController@index');
    $router->get('ver/{id}', 'AuthController@show');
    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');
});

//HSEQ:
$router->group(['prefix' => 'hseq'], function () use ($router) {
    $router->get('ver', 'HseqController@index');
    $router->get('ver/{id}', 'HseqController@show');
    $router->post('new', 'HseqController@create');
    $router->put('update/{id}', 'HseqController@update');
    $router->delete('delete/{id}', 'HseqController@delete');
    $router->get('search', 'HseqController@search');

});

//Cargo:
$router->group(['prefix' => 'cargo'], function () use ($router) {
    $router->get('ver', 'CargoController@index');
    $router->get('ver/{id}', 'CargoController@show');
    $router->post('new', 'CargoController@create');
    $router->put('update/{id}', 'CargoController@update');
    $router->delete('delete/{id}', 'CargoController@delete');
});

//Informe:
$router->group(['prefix' => 'informe'], function () use ($router) {
    $router->get('ver', 'InformeController@index');
    $router->get('ver/{id}', 'InformeController@show');
    $router->post('new', 'InformeController@create');
    $router->put('update/{id}', 'InformeController@update');
    $router->delete('delete/{id}', 'InformeController@delete');

});

//Estado
$router->group(['prefix' => 'estado'], function () use ($router) {
    $router->get('ver', 'EstadoController@index');
    $router->get('ver/{id}', 'EstadoController@show');
    $router->post('new', 'EstadoController@create');
    $router->put('update/{id}', 'EstadoController@update');
    $router->delete('delete/{id}', 'EstadoController@delete');

});
//Prioridad
$router->group(['prefix' => 'prioridad'], function () use ($router) {
    $router->get('ver', 'PrioridadController@index');
    $router->get('ver/{id}', 'PrioridadController@show');
    $router->post('new', 'PrioridadController@create');
    $router->put('update/{id}', 'PrioridadController@update');
    $router->delete('delete/{id}', 'PrioridadController@delete');

});
//Zona
$router->group(['prefix' => 'zona'], function () use ($router) {
    $router->get('ver', 'ZonaController@index');
    $router->get('ver/{id}', 'ZonaController@show');
    $router->post('new', 'ZonaController@create');
    $router->put('update/{id}', 'ZonaController@update');
    $router->delete('delete/{id}', 'ZonaController@delete');

});

//rutas que solo pueden entrar los usuarios con token en header
$router->group(['middleware' => 'jwt-auth'], function ($app) {
    $app->get('/test', function () {
        return response()->json([
            'message' => 'Holi! Usuario autorizado',
        ]);
    });
    $app->get('/user', 'AuthController@test');
});
