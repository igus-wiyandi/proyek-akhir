<?php


use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginAdminController;
use App\Http\Controllers\LoginGuruController;
use App\Http\Controllers\MapelKelas10Controller;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PerhitunganGajiController;
use App\Http\Controllers\Status10Controller;
use App\Http\Controllers\Status11Controller;
use App\Http\Controllers\Status12Controller;
use App\Http\Controllers\TampilGuruController;
use App\Http\Middleware\LoggedInGuru;
use App\Http\Middleware\LoginCheckAdmin;
use App\Http\Middleware\LoggedInAdmin;
use App\Http\Middleware\LoginCheckGuru;
use App\Http\Controllers\MapelKelas11Controller;
use App\Http\Controllers\MapelKelas12Controller;
use Illuminate\Support\Facades\Route;


Route::middleware(LoggedInGuru::class)->group(function () {
    Route::resource('status10', Status10Controller::class);
    Route::resource('status11', Status11Controller::class);
    Route::resource('status12', Status12Controller::class);
    Route::resource('tampilGuru', TampilGuruController::class);
    Route::get('/logoutGuru', [LoginGuruController::class, 'logoutGuru'])->name('logoutGuru');
});

Route::middleware(LoginCheckGuru::class)->group(function () {
    Route::get('/', [LoginGuruController::class, 'loginGuru'])->name('loginGuru');
    Route::post('/prosesloginGuru', [LoginGuruController::class, 'prosesloginGuru'])->name('prosesloginGuru');
    Route::get('/register', [LoginGuruController::class, 'registerGuru'])->name('registerguru');
    Route::post('/registerGuru', [LoginGuruController::class, 'prosesregisGuru'])->name('prosesregisguru');
});

Route::middleware(LoginCheckAdmin::class)->group(function () {
    Route::get('/loginAdmin', [LoginAdminController::class, 'loginAdmin'])->name('loginAdmin');
    Route::post('/prosesloginAdmin', [LoginAdminController::class, 'prosesloginAdmin'])->name('prosesloginAdmin');
});

Route::get('/mapel/{id}/pilih-guru', [MapelKelas10Controller::class, 'showPilihGuru']);
Route::post('/mapel/{id}/pilih-guru', [MapelKelas10Controller::class, 'simpanGuru']);

Route::get('/mapel11/{id}/pilih-guru11', [MapelKelas11Controller::class, 'showPilihGuru11']);
Route::post('/mapel11/{id}/pilih-guru11', [MapelKelas11Controller::class, 'simpanGuru11']);

Route::get('/mapel12/{id}/pilih-guru12', [MapelKelas12Controller::class, 'showPilihGuru12']);
Route::post('/mapel12/{id}/pilih-guru12', [MapelKelas12Controller::class, 'simpanGuru12']);

Route::middleware(LoggedInAdmin::class)->group(function () {
    Route::post('/absensi/preview', [AbsensiController::class, 'preview'])->name('absensi.preview');
    Route::post('/perhitungan_gaji/range', [PerhitunganGajiController::class, 'filterByDateRange'])->name('perhitungan_gaji.range');
    Route::resource('admin', AdminController::class);
    Route::resource('guru', GuruController::class) ->except(['show']);
    Route::resource('mapel', MapelKelas10Controller::class);
    Route::resource('mapel11', MapelKelas11Controller::class);
    Route::resource('mapel12', MapelKelas12Controller::class);
    Route::resource('absensi', AbsensiController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('laporan', LaporanController::class);
    Route::resource('perhitungan_gaji', PerhitunganGajiController::class);
    Route::get('/logoutAdmin', [LoginAdminController::class, 'logoutAdmin'])->name('logoutAdmin');
});

Route::get('/guru/layout', [GuruController::class, 'layout'])->name('guru.layout');

Route::get('/guru/info', [GuruController::class, 'infoguru'])->name('guru.info');



