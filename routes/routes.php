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
    'prefix'=>'order',
    'middleware'=>'auth'
],function($router){
    $router->get('',[
        'as'=>'order.list',
        'uses'=>'OrderController@getList'
    ]);
    $router->get('/{id:[0-9]+}',[
        'as'=>'order.detail',
        'uses'=>'OrderController@detail'
    ]);
    $router->post('/{id:[0-9]+}',[
        'as'=>'order.update',
        'uses'=>'OrderController@update'
    ]);
    $router->post('',[
        'as'=>'order.create',
        'uses'=>'OrderController@create'
    ]);
    $router->delete('/{id:[0-9]+}',[
        'as'=>'order.delete',
        'uses'=>'OrderController@delete'
    ]);
});
$router->group([
    'prefix'=>'variant',
    'middleware'=>'auth'
],function($router){
//    $router->get('',[
//        'as'=>'variant.list',
//        'uses'=>'VariantController@getList'
//    ]);
//    $router->get('/{id:[0-9]+}',[
//        'as'=>'variant.detail',
//        'uses'=>'VariantController@detail'
//    ]);
//    $router->put('/{id:[0-9]+}',[
//        'as'=>'variant.update',
//        'uses'=>'VariantController@update'
//    ]);
//    $router->post('',[
//        'as'=>'variant.create',
//        'uses'=>'VariantController@create'
//    ]);
//    $router->delete('/{id:[0-9]+}',[
//        'as'=>'variant.delete',
//        'uses'=>'VariantController@delete'
//    ]);
    $router->get('/{id:[0-9]+}/value',[
        'as'=>'variant.detail.value',
        'uses'=>'VariantController@getValueByVariant'
    ]);
    $router->get('/option',[
        'as'=>'variant.option',
        'uses'=>'VariantController@getOption'
    ]);
});
