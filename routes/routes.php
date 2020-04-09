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
    $router->post('',[
        'middleware'=>"perm:dashboard_list",
        'as'=>'user.create',
        'uses'=>'UserController@create'
    ]);
    $router->put('/{id:[0-9]+}',[
        'as'=>'user.update',
        'uses'=>'UserController@update'
    ]);
    $router->get('/{id:[0-9]+}',[
        'as'=>'user.detail',
        'uses'=>'UserController@detail'
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

$router->group([
    'prefix'=>'category',
    'middleware'=>'auth'
],function ($router){
    $router->get('',[
        'as'=>'category.list',
        'uses'=>'CategoryController@getList'
    ]);
    $router->post('',[
        'as'=>'category.create',
        'uses'=>'CategoryController@create'
    ]);
    $router->put('/{id:[0-9]+}',[
        'as'=>'category.update',
        'uses'=>'CategoryController@update'
    ]);
    $router->get('/{id:[0-9]+}',[
        'as'=>'category.detail',
        'uses'=>'CategoryController@detail'
    ]);
    $router->delete('/{id:[0-9]+}',[
        'as'=>'category.delete',
        'uses'=>'CategoryController@delete'
    ]);
    $router->get('/option',[
        'as'=>'category.option',
        'uses'=>'CategoryController@getOption'
    ]);
});

$router->group([
   'prefix'=>'item',
   'middleware'=>'auth'
],function($router){
    $router->get('',[
       'as'=>'item.list',
       'uses'=>'ItemController@getList'
    ]);
    $router->get('/{id:[0-9]+}',[
        'as'=>'item.detail',
        'uses'=>'ItemController@detail'
    ]);
    $router->post('/{id:[0-9]+}',[
        'as'=>'item.update',
        'uses'=>'ItemController@update'
    ]);
    $router->post('',[
        'as'=>'item.create',
        'uses'=>'ItemController@create'
    ]);
    $router->delete('/{id:[0-9]+}',[
       'as'=>'item.delete',
       'uses'=>'ItemController@delete'
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
