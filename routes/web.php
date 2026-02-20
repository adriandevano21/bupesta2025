<?php

use App\Http\Controllers\CinemaController;
use App\Http\Controllers\JazirahController;
use App\Http\Controllers\DashboardActivityController;
use App\Http\Controllers\DashboardkinerjaController;
use App\Http\Controllers\Jazirah2Controller;
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
    Route::get('/api-useractivities-bupesta', 'api_useractivities');
});

Route::controller(JazirahController::class)->group(function () {
    // Route::get('/jazirah', 'index');
    Route::get('/dashboard-jazirah', 'dashboard');
    Route::get('/form-jazirah', 'input');
    Route::get('/qna-jazirah', 'qna');
    Route::get('/narahubung-jazirah', 'narahubung')->name('narahubung.narahubung');
    Route::get('/narahubung-jazirah/data')->name('narahubung.data');
});

Route::controller(Jazirah2Controller::class)->group(function () {
    Route::get('/jazirah', 'index');
    Route::get('/jazirah-lembarkerja', 'lembarkerja')->name('jazirah.lembarkerja');
    Route::put('/isian/{id}', 'update')->name('isian.update');
    Route::get('/newjazirah-lembarkerja', 'newlembarkerja')->name('newjazirah.lembarkerja');
    Route::get('/admin-jazirah', 'admin')->name('admin.jazirah');
    Route::post('/setting-evaluator', 'settingevaluator')->name('setting.evaluator');
    Route::post('/google-drive/files', 'getFileList');
});
