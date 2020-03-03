<?php

Route::put('password', 'UserController@updatePassword');
Route::put('email', 'UserController@updateEmail');
Route::post('send-delete', 'UserController@sendDelete');
Route::post('delete', 'UserController@destroy');
