<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\App;
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

Route::get('/', function () {
    return view('pages.inicio');
})->name('inicio');

Route::post('/busqueda', [WebController::class, 'busqueda'])->name('busqueda');
Route::get('/servicios', [WebController::class, 'servicios'])->name('servicios');
Route::post('/informe', [WebController::class, 'informe'])->name('informe');
Route::get('/uploadRemates', [WebController::class, 'uploadRemates'])->name('uploadRemates');
Route::get('/uploadInscripciones', [WebController::class, 'uploadInscripciones'])->name('uploadInscripciones');
Route::get('/uploadRevisiones', [WebController::class, 'uploadRevisiones'])->name('uploadRevisiones');
Route::get('/uploadPRT', [WebController::class, 'uploadPRT'])->name('uploadPRT');
Route::get('/uploadPermisos', [WebController::class, 'uploadPermisos'])->name('uploadPermisos');
Route::get('/uploadRecall', [WebController::class, 'uploadRecall'])->name('uploadRecall');
Route::get('/uploadTransporte', [WebController::class, 'uploadTransporte'])->name('uploadTransporte');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/chart', [App\Http\Controllers\HomeController::class, 'chart'])->name('chart');

