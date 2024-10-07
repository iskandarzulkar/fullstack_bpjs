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
Route::get('/', [\App\Http\Controllers\EmployeeController::class, 'index'])->name('index');
Route::get('/create', [\App\Http\Controllers\EmployeeController::class, 'createFakerData'])->name('faker');
Route::get('/data/load', [\App\Http\Controllers\EmployeeController::class, 'getDataEmploye'])->name('load');
Route::get('/export-pdf', [\App\Http\Controllers\EmployeeController::class, 'exportPdf'])->name('export-pdf');
Route::get('/generate-pdf', [\App\Http\Controllers\EmployeeController::class, 'textGenereatePdf'])->name('test-pdf');