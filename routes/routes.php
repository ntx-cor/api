<?php
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

$router->group([
    'prefix'=>'user',
    'middleware'=>'auth'
],function($router){
    $router->get('',[
        'as'=>'user.list',
        'uses'=>'UserController@getListUser'
    ]);
    $router->get('/{id:[0-9]+}',[
        'as'=>'user.detail',
        'uses'=>'UserController@getUser'
    ]);
});
