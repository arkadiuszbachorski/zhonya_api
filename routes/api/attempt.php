<?php

Route::get('', 'AttemptController@index');
Route::post('', 'AttemptController@store');
Route::get('{attempt}/edit', 'AttemptController@edit')->middleware('can:manage,attempt');
Route::get('{attempt}/name', 'AttemptController@name')->middleware('can:manage,attempt');
Route::put('{attempt}', 'AttemptController@update')->middleware('can:manage,attempt');
Route::delete('{attempt}', 'AttemptController@destroy')->middleware('can:manage,attempt');
Route::post('{attempt}/measurement/save', 'AttemptController@measurementSave')->middleware('can:manage,attempt');
Route::get('{attempt}/measurement', 'AttemptController@measurement')->middleware('can:manage,attempt');

