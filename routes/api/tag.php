<?php

Route::get('', 'TagController@index');
Route::post('', 'TagController@store');
Route::get('{tag}', 'TagController@edit')->middleware('can:manage,tag');
Route::put('{tag}', 'TagController@update')->middleware('can:manage,tag');
Route::delete('{tag}', 'TagController@destroy')->middleware('can:manage,tag');

