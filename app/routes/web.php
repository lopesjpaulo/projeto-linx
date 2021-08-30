<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['middleware' => 'app\Http\Middleware\AuthenticateAccess'], function() use ($router) {
    $router->post('/client', 'ClientController@create');
    $router->get('/client', 'ClientController@get');
    $router->get('/client/{id}', 'ClientController@show');
    $router->put('/client/{id}', 'ClientController@update');
    $router->delete('/client/{id}', 'ClientController@delete');

    $router->post('/account', 'AccountController@create');
    $router->get('/account', 'AccountController@get');
    $router->post('/account/deposit', 'AccountController@deposit');
    $router->post('/account/withdraw', 'AccountController@withdraw');
});
