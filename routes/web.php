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

Route::resource('annonces', 'AdController')
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
    Route::post('images-save', 'UploadImagesController@store')->name('save-images');
    Route::delete('images-delete', 'UploadImagesController@destroy')->name('destroy-images');
    Route::get('images-server','UploadImagesController@getServerImages')->name('server-images');
});


Route::prefix('admin')->middleware('admin')->group(function(){
    Route::get('/', 'AdminController@index')->name('admin.index');
    Route::prefix('annonces')->group(function(){
        Route::get('/', 'AdminController@ads')->name('admin.ads');
        Route::get('obsoletes', 'AdminController@obsoletes')->name('admin.obsoletes');
        Route::middleware('ajax')->group(function () {
            Route::post('approve/{ad}', 'AdminController@approve')->name('admin.approve');
            Route::post('refuse', 'AdminController@refuse')->name('admin.refuse');
        });
    });
    Route::prefix('messages')->group(function () {
        Route::get('/', 'AdminController@messages')->name('admin.messages');
        Route::post('approve/{message}', 'AdminController@messageApprove')->name('admin.message.approve');
        Route::post('refuse', 'AdminController@messageRefuse')->name('admin.message.refuse');
    });
});

Route::prefix('admin/annonces')->group(function () {
    Route::middleware('ajax')->group(function () {
        Route::post('addweek/{ad}', 'AdminController@addWeek')->name('admin.addweek');
        Route::delete('destroy/{ad}', 'AdminController@destroy')->name('admin.destroy');
    });
});


Route::prefix('utilisateur')->middleware('user')->group(function () {
    Route::get('/', 'UserController@index')->name('user.index');
    Route::prefix('annonces')->group(function () {
        Route::get('actives', 'UserController@actives')->name('user.actives');
        Route::get('obsoletes', 'UserController@obsoletes')->name('user.obsoletes');
        Route::get('attente', 'UserController@attente')->name('user.attente');
    });
    Route::prefix('profil')->group(function () {
        Route::get('email', 'UserController@emailEdit')->name('user.email.edit');
        Route::put('email', 'UserController@emailUpdate')->name('user.email.update');
        Route::get('donnees', 'UserController@data')->name('user.data');
    });
});

Route::view('legal', 'legal')->name('legal');
Route::view('confidentialite', 'confidentialite')->name('confidentialite');