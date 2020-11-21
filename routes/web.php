<?php

use Illuminate\Support\Facades\Route;

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
    return view('pedido');
})->name('pedido');

Route::post('/pagamento', function () {
    return view('pagamento');
})->name('pagamento');


Route::post('/', function () {
    return abort(404);
})->name('pedido');
Route::get('/pagamento', function () {
    return abort(404);
})->name('pagamento');

