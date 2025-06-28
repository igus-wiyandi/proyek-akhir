<?php


use App\Http\Controllers\adminController;
use App\Http\Controllers\guruController;
use App\Http\Controllers\kategoriController;
use App\Http\Controllers\login_adminController;
use App\Http\Controllers\login_guruController;
use App\Http\Controllers\mapelController;
use App\Http\Controllers\absensiController;
use App\Http\Controllers\jabatanController;
use App\Http\Controllers\laporanController;
use App\Http\Controllers\perhitungan_gajiController;
use App\Http\Controllers\status10Controller;
use App\Http\Controllers\status11Controller;
use App\Http\Controllers\status12Controller;
use App\Http\Controllers\tampilGuruController;
use App\Http\Middleware\LoggedInGuru;
use App\Http\Middleware\LoginCheckAdmin;
use App\Http\Middleware\LoggedInAdmin;
use App\Http\Middleware\LoginCheckGuru;
use App\Http\Controllers\mapel11Controller;
use App\Http\Controllers\mapel12Controller;
use Illuminate\Support\Facades\Route;


Route::middleware(LoggedInGuru::class)->group(function () {
    Route::resource('status10', status10Controller::class);
    Route::resource('status11', status11Controller::class);
    Route::resource('status12', status12Controller::class);
    Route::resource('tampilGuru', tampilGuruController::class);
    Route::get('/logoutGuru', [login_guruController::class, 'logoutGuru'])->name('logoutGuru');

});

Route::middleware(LoginCheckGuru::class)->group(function () {
    Route::get('/', [login_guruController::class, 'loginGuru'])->name('loginGuru');
    Route::post('/prosesloginGuru', [login_guruController::class, 'prosesloginGuru'])->name('prosesloginGuru');
});

Route::middleware(LoginCheckAdmin::class)->group(function () {
    Route::get('/loginAdmin', [login_adminController::class, 'loginAdmin'])->name('loginAdmin');
    Route::post('/prosesloginAdmin', [login_adminController::class, 'prosesloginAdmin'])->name('prosesloginAdmin');
});

Route::get('/mapel/{id}/pilih-guru', [mapelController::class, 'showPilihGuru']);
Route::post('/mapel/{id}/pilih-guru', [mapelController::class, 'simpanGuru']);

Route::get('/mapel11/{id}/pilih-guru11', [mapel11Controller::class, 'showPilihGuru11']);
Route::post('/mapel11/{id}/pilih-guru11', [mapel11Controller::class, 'simpanGuru11']);

Route::get('/mapel12/{id}/pilih-guru12', [mapel12Controller::class, 'showPilihGuru12']);
Route::post('/mapel12/{id}/pilih-guru12', [mapel12Controller::class, 'simpanGuru12']);

Route::middleware(LoggedInAdmin::class)->group(function () {
    Route::post('/absensi/preview', [AbsensiController::class, 'preview'])->name('absensi.preview');
    Route::post('/perhitungan_gaji/range', [perhitungan_gajiController::class, 'filterByDateRange'])->name('perhitungan_gaji.range');

Route::resource('admin', adminController::class);
Route::resource('guru', guruController::class);
Route::resource('mapel', mapelController::class);
Route::resource('mapel11', mapel11Controller::class);
Route::resource('mapel12', mapel12Controller::class);
Route::resource('absensi', absensiController::class);
Route::resource('kategori', kategoriController::class);
Route::resource('jabatan', jabatanController::class);
Route::resource('laporan', laporanController::class);
Route::resource('perhitungan_gaji', perhitungan_gajiController::class);
Route::get('/logoutAdmin', [login_adminController::class, 'logoutAdmin'])->name('logoutAdmin');
});

