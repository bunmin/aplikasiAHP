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
Route::get('/home', 'HomeController@index')->name('home.index');

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
Route::get('/matrix/kriteria/{topikId}', 'MatrixController@matrixKriteria')->name('matrix.kriteria');
Route::get('/matrix/kriteria/getall/{topikId}', 'MatrixController@KriteriaGetAll')->name('matrix.kriteria.getall');
Route::get('/matrix/kriteria/getanother/{topikId}', 'MatrixController@KriteriaGetAnother')->name('matrix.kriteria.getanother');
Route::POST('/matrix/kriteria/updatekriteriabobot/{topikId}', 'MatrixController@updateKriteriaBobot')->name('matrix.kriteria.updatebobot');
Route::get('/matrix/kriteria/getcr/{topikId}', 'MatrixController@getKriteriaCR')->name('matrix.kriteria.getcr');
Route::get('/matrix/kriteria/cekhasil/{topikId}', 'MatrixController@cekKriteriaHasil')->name('matrix.kriteria.cekhasil');
Route::get('/matrix/kriteria/getnilai/{topikId}', 'MatrixController@getKriteriaNilai')->name('matrix.kriteria.getnilai');

Route::get('/matrix/alternatif/{topikId}/{kriteriaId}', 'MatrixController@matrixAlternatif')->name('matrix.alternatif');
Route::get('/matrix/getalternatif/getall/{topikId}/{kriteriaId}', 'MatrixController@AlternatifGetAll')->name('matrix.alternatif.getall');
Route::get('/matrix/getalternatif/getanother/{topikId}', 'MatrixController@AlternatifGetAnother')->name('matrix.alternatif.getanother');
Route::POST('/matrix/updatealternatifbobot/{topikId}/{kriteriaId}', 'MatrixController@updateAlternatifBobot')->name('matrix.alternatif.updatebobot');
Route::get('/matrix/getalternatif/getcr/{topikId}/{kriteriaId}', 'MatrixController@getAlternatifCR')->name('matrix.alternatif.getcr');
Route::get('/matrix/getalternatif/cekhasil/{topikId}/{kriteriaId}', 'MatrixController@cekAlternatifHasil')->name('matrix.alternatif.cekhasil');
Route::get('/matrix/getalternatif/getnilai/{topikId}', 'MatrixController@getAlternatifNilai')->name('matrix.alternatif.getnilai');

// Route::get('/matrix/hitungtotal/{topikId}', 'MatrixController@hitungTotal')->name('matrix.hitungtotal');
Route::get('/gettotal', 'MatrixController@getTotal')->name('matrix.gettotal');
