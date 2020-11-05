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

$router->group(['prefix' => 'member'], function () use ($router) {
    $router->post('/register', 'MemberController@register');
});

$router->group(['prefix' => 'wallet'], function () use ($router) {
    $router->get('/balance', 'WalletController@balance');
});
