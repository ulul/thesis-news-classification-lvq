<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home.index');

Route::group(['prefix' => 'training'], function () {
    Route::get('/', 'TrainingController@index')->name('training.index');
    Route::get('create', 'TrainingController@create')->name('training.create');
    Route::get('detail/{id}', 'TrainingController@show')->name('training.show');
    Route::get('edit/{id}', 'TrainingController@edit')->name('training.edit');
    Route::post('store', 'TrainingController@store')->name('training.store');
    Route::put('update/{id}', 'TrainingController@update')->name('training.update');
    Route::delete('hapus', 'TrainingController@destroy')->name('training.destroy'); 
});

Route::group(['prefix' => 'testing'], function () {
    Route::get('/', 'TestingController@index')->name('testing.index');
});

Route::group(['prefix' => 'category'], function () {
    Route::post('store', 'CategoryController@store')->name('category.store');
    Route::put('update', 'CategoryController@update')->name('category.update');
    Route::delete('delete', 'CategoryController@destroy')->name('category.destroy');
});
