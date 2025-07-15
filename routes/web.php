<?php

use App\Http\Controllers\CinemaController;
use App\Http\Controllers\DashboardActivityController;
use App\Http\Controllers\DashboardkinerjaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(CinemaController::class)->group(function () {
    Route::get('/', 'cinema');
    Route::get('/cinema', 'cinema')->name('cinema.cinema');
    Route::get('/cinema/search', 'search')->name('cinema.search');
    Route::post('/cinema', 'TambahData');
    Route::post('/edit-kegiatan/{id}', 'EditData');
    Route::delete('/hapus-kegiatan/{id}', 'HapusData');
    Route::get('/detail/{id}', 'detail');
    Route::post('/login', 'authenticate');
    Route::post('/logout', 'logout');
});

Route::controller(DashboardActivityController::class)->group(function () {
    Route::get('/activity-user', 'index')->name('user-activity');
});
