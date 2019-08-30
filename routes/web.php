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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::resource('annonce', 'AdController')
    ->parameters([
        'annonce' => 'ad'
    ])->except([
    'index', 'show', 'destroy'
]);

Route::prefix('annonces')->group(function(){
    Route::get('voir/{ad}', 'AdController@show')->name('annonces.show');
    Route::get('{region?}/{departement?}/{commune?}', 'AdController@index')->name('annonces.index');
    Route::post('recherche', 'AdController@search')->name('annonces.search')->middleware('ajax');

});


Route::middleware('ajax')->group(function(){
    Route::post('message', 'UserController@message')->name('message');
});