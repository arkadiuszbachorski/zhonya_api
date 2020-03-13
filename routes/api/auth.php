<?php

Route::post('login', 'Auth\LoginController@login');
Route::post('register', 'Auth\RegisterController@register');
Route::post('reset', 'Auth\PasswordResetController@reset');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('send', 'Auth\VerificationController@send');
    Route::post('verify', 'Auth\VerificationController@verify');
});
