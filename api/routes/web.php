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

$router->group(['prefix' => 'dashboard'], function () use ($router) {
});

$router->group(['prefix' => 'global'], function () use ($router) {
    $router->get('getMenu',  ['uses' => 'GlobalController@getMenu']);
    $router->get('getRole',  ['uses' => 'GlobalController@getRole']);
});

$router->group(['prefix' => 'settings'], function () use ($router) {
    $router->get('get',  ['uses' => 'SettingController@getAll']);
    $router->post('doSave',  ['uses' => 'SettingController@doSave']);
    $router->post('doDelete',  ['uses' => 'SettingController@doDelete']);
});

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('doLogin',  ['uses' => 'AuthController@doLogin']);
    $router->post('doLogout',  ['uses' => 'AuthController@doLogout']);
    $router->get('doAuth',  ['uses' => 'AuthController@doAuth']);
    $router->post('doReset',  ['uses' => 'AuthController@doReset']);
});

$router->group(['prefix' => 'users'], function () use ($router) {
    $router->get('get',  ['uses' => 'UserController@getAll']);
    $router->post('doSave',  ['uses' => 'UserController@doSave']);
    $router->post('doDelete',  ['uses' => 'UserController@doDelete']);
});

$router->group(['prefix' => 'roles'], function () use ($router) {
    $router->get('get',  ['uses' => 'RoleController@getAll']);
    $router->get('getSelected',  ['uses' => 'RoleController@getSelected']);
    $router->post('doSave',  ['uses' => 'RoleController@doSave']);
    $router->post('doDelete',  ['uses' => 'RoleController@doDelete']);
});

$router->group(['prefix' => 'products'], function () use ($router) {
    $router->get('get',  ['uses' => 'ProductController@getAll']);
    $router->post('doSave',  ['uses' => 'ProductController@doSave']);
    $router->post('doUpload',  ['uses' => 'ProductController@doUpload']);
    $router->post('doDelete',  ['uses' => 'ProductController@doDelete']);
    $router->get('doLookup',  ['uses' => 'ProductController@doLookup']);
});

$router->group(['prefix' => 'customer'], function () use ($router) {
    $router->get('get',  ['uses' => 'CustomerController@getAll']);
    $router->post('doSave',  ['uses' => 'CustomerController@doSave']);
    $router->post('doUpload',  ['uses' => 'CustomerController@doUpload']);
    $router->post('doDelete',  ['uses' => 'CustomerController@doDelete']);
    $router->get('doLookup',  ['uses' => 'CustomerController@doLookup']);
});

$router->group(['prefix' => 'stock'], function () use ($router) {
    $router->get('get',  ['uses' => 'StockController@getAll']);
    $router->post('doSave',  ['uses' => 'StockController@doSave']);
    $router->post('doUpload',  ['uses' => 'StockController@doUpload']);
    $router->post('doDelete',  ['uses' => 'StockController@doDelete']);
    $router->get('doLookup',  ['uses' => 'StockController@doLookup']);
});

$router->group(['prefix' => 'reports'], function () use ($router) {
    $router->get('getPurchasing',  ['uses' => 'ReportController@getPurchasing']);
    $router->get('getCustomer',  ['uses' => 'ReportController@getCustomer']);
    $router->get('getStock',  ['uses' => 'ReportController@getStock']);
});
