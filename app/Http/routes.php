<?php

Route::get('/', ['as' => 'main', 'middleware' => ['auth','cache.dynamic'], 'uses' => 'IndexController@index']);

Route::get('login', ['as' => 'login', 'uses' => 'AuthController@loginPage']);
Route::get('login/vk', ['as' => 'loginVk', 'uses' => 'AuthController@getAuthorizationFirst']);
Route::get('login/callback', ['as' => 'callback', 'uses' => 'AuthController@loginSocialUser']);
Route::get('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

Route::get('setting', ['as' => 'setting', 'middleware' => ['auth','cache.dynamic'], 'uses' => 'SettingController@index']);
Route::post('setting/add', 'SettingController@addMessage');
Route::post('setting/del', 'SettingController@deleteMessage');
Route::post('setting/token', 'SettingController@updateToken');