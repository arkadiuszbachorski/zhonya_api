<?php

Route::put('password', 'UserController@updatePassword');
Route::put('email', 'UserController@updateEmail');
Route::delete('', 'UserController@delete');
