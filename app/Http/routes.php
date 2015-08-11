<?php

Route::get('/', ['as' => 'main', 'middleware' => 'auth', 'uses' => 'IndexController@index']);

Route::get('login', ['as' => 'login', 'uses' => 'AuthController@loginPage']);
Route::get('login/{provider?}', ['as' => 'loginProvider', 'uses' => 'AuthController@preLogin']);
Route::get('logout', ['as' => 'logout', 'middleware' => 'auth', 'uses' => 'AuthController@logout']);

Route::get('setting', ['as' => 'setting', 'middleware' => 'auth', 'uses' => 'SettingController@index']);
Route::post('setting/add', 'SettingController@addMessage');
Route::post('setting/del', 'SettingController@deleteMessage');
Route::post('setting/token', 'SettingController@updateToken');