<?php

/** @var \Laravel\Lumen\Routing\Router $users */

$users->post('/register', 'AuthController@register');
$users->post('/login', 'AuthController@login');

$users->group(['middleware' => 'JwtMiddleware'], function ($users) {

    $users->group(['prefix' => 'users'], function () use ($users) {

        $users->get('/', 'users\UserController@getUsers');
        $users->get('/{id}', 'users\UserController@getUserById');
        $users->post('/', 'users\UserController@createUser');
        $users->put('/{id}', 'users\UserController@updateUser');
        $users->delete('/{id}', 'users\UserController@deleteUser');
        
    });

});