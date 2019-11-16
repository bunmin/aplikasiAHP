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

// Route::get('/', function () {
//     return view('layouts.app');
// });
Auth::routes();

Route::get('/', 'HomeController@index')->name('home.index');
Route::get('/topik', 'TopikController@index')->name('topik.index');
Route::post('/topik', 'TopikController@storeTopik')->name('topik.tambah');
