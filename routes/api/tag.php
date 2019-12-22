<?php

Route::get('', 'TagController@index');
Route::post('', 'TagController@store');
Route::get('{tag}/edit', 'TagController@edit')->middleware('can:manage,tag');
Route::get('{tag}/name', 'TagController@name')->middleware('can:manage,tag');
Route::get('{tag}/attach-tasks', 'TagController@attachTasks')->middleware('can:manage,tag');
Route::put('{tag}', 'TagController@update')->middleware('can:manage,tag');
Route::delete('{tag}', 'TagController@destroy')->middleware('can:manage,tag');

