<?php
$router = app('router');
$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->group([
    'prefix'=>'auth',
],function($router){
    $router->post('login',[
        'as'=>'auth.login',
        'uses'=>'AuthController@login'
    ]);
    $router->post('logout',[
        'as'=>'auth.login',
        'uses'=>'AuthController@logout'
    ]);
    $router->get('',[
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
        'middleware'=>"perm:dashboard_list",
        'as'=>'user.list',
        'uses'=>'UserController@getListUser'
    ]);
    $router->get('/{id:[0-9]+}',[
        'as'=>'user.detail',
        'uses'=>'UserController@getUser'
    ]);
    $router->get('menu',[
        'as'=>'user.menu',
        'uses'=>'MenuController@getMenuByUser'
    ]);
    $router->get('permission',[
        'as'=>'user.permission',
        'uses'=>'UserController@getPermission'
    ]);
});
