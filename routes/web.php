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
Route::get('/topik/getsingle/{topikid}', 'TopikController@getsingleTopik')->name('topik.getsingle');
Route::get('/topik/detail/{topikId}', 'TopikController@detail')->name('topik.detail');

Route::post('/topik/kriteria/store/{topikId}', 'TopikController@storeKriteria')->name('topik.kriteria.tambah');
Route::get('/topik/kriteria/get', 'TopikController@getKriteria')->name('topik.kriteria.get');
Route::post('/topik/kriteria/edit/{tenderId}', 'TopikController@updateKriteria')->name('topik.kriteria.update');
Route::post('/topik/kriteria/del/{tenderId}', 'TopikController@deleteKriteria')->name('topik.kriteria.delete');

Route::post('/topik/alternatif/store/{topikId}', 'TopikController@storeAlternatif')->name('topik.alternatif.tambah');
Route::get('/topik/alternatif/get', 'TopikController@getAlternatif')->name('topik.alternatif.get');
Route::post('/topik/alternatif/edit/{tenderId}', 'TopikController@updateAlternatif')->name('topik.alternatif.update');
Route::post('/topik/alternatif/del/{tenderId}', 'TopikController@deleteAlternatif')->name('topik.alternatif.delete');

Route::get('/matrix/{topikId}', 'MatrixController@index')->name('matrix.index');
