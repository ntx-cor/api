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
$router = app('router');
$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group([
    'prefix'=>'auth',
],function($route){
    $route->post('login',[
        'as'=>'auth.login',
        'uses'=>'AuthController@login'
    ]);
    $route->post('logout',[
        'as'=>'auth.login',
        'uses'=>'AuthController@logout'
    ]);
    $route->get('',[
        'as'=>'auth.login',
        'uses'=>'AuthController@info',
        'middleware'=>'auth'
    ]);
});
