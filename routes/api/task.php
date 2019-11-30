<?php

Route::get('', 'TaskController@index');
Route::post('', 'TaskController@store');
Route::get('{task}/edit', 'TaskController@edit')->middleware('can:manage,task');
Route::put('{task}', 'TaskController@update')->middleware('can:manage,task');
Route::delete('{task}', 'TaskController@destroy')->middleware('can:manage,task');

