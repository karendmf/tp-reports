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
$router->post('login', 'AuthController@login');
$router->post('register', 'AuthController@register');

$router->group(['middleware' => 'jwt-auth'], function($app)
{
    $app->get('/test', function() {
        return response()->json([
            'message' => 'Holi!',
        ]);
    });
    $app->get('/user', 'AuthController@test');
});