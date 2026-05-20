<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DosenMiddleware;
use App\Http\Middleware\MahasiswaMiddleware;

use App\Http\Controllers\ManageAkunController;
use App\Http\Controllers\ImportMahasiswaController;
use App\Http\Controllers\ManageKelasController;
use App\Http\Controllers\ListKuesionerController;
use App\Http\Controllers\TestGayaBelajarController;
use App\Http\Controllers\HasilKuesionerController;
use App\Http\Controllers\KelasMahasiswaController;
use App\Http\Controllers\DosenKelasController;
use App\Http\Controllers\KelompokController;

/*
|--------------------------------------------------------------------------
| AUTH / LANDING
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', AdminMiddleware::class])->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');


    // =========================
    // MANAGE USER
    // =========================

    Route::get('/admin/list-user', [ManageAkunController::class, 'index'])
        ->name('admin.list-user');

    Route::get('/admin/create-user', [ManageAkunController::class, 'create'])
        ->name('admin.create-user');

    Route::post('/admin/users', [ManageAkunController::class, 'store'])
        ->name('admin.store-user');

    Route::get('/admin/users/{id}/edit', [ManageAkunController::class, 'edit'])
        ->name('admin.edit-user');

    Route::put('/admin/users/{id}', [ManageAkunController::class, 'update'])
        ->name('admin.update-user');

    Route::delete('/admin/users/{id}', [ManageAkunController::class, 'destroy'])
        ->name('admin.destroy-user');


    // =========================
    // IMPORT MAHASISWA
    // =========================

    Route::get('/admin/import-mahasiswa', [ImportMahasiswaController::class, 'index'])
        ->name('admin.import-mahasiswa-form');

    Route::post('/admin/import-mahasiswa', [ImportMahasiswaController::class, 'import'])
        ->name('admin.import-mahasiswa');


    // =========================
    // MANAGE KELAS
    // =========================

    Route::get('/admin/list-kelas', [ManageKelasController::class, 'index'])
        ->name('admin.list-kelas');

    Route::get('/admin/create-kelas', [ManageKelasController::class, 'create'])
        ->name('admin.create-kelas');

    Route::post('/admin/kelas', [ManageKelasController::class, 'store'])
        ->name('admin.store-kelas');

    Route::get('/admin/kelas/{id}/edit', [ManageKelasController::class, 'edit'])
        ->name('admin.edit-kelas');

    Route::put('/admin/kelas/{id}', [ManageKelasController::class, 'update'])
        ->name('admin.update-kelas');

    Route::delete('/admin/kelas/{id}', [ManageKelasController::class, 'destroy'])
        ->name('admin.destroy-kelas');


    // =========================
    // LIST KUESIONER
    // =========================

    Route::get('/admin/list-kuesioner', [ListKuesionerController::class, 'index'])
        ->name('admin.list-kuesioner');

    Route::get('/admin/list-kuesioner/create', [ListKuesionerController::class, 'create'])
        ->name('admin.create-kuesioner');

    Route::post('/admin/list-kuesioner/store', [ListKuesionerController::class, 'store'])
        ->name('admin.store-kuesioner');

    Route::get('/admin/list-kuesioner/{id}/edit', [ListKuesionerController::class, 'edit'])
        ->name('admin.edit-kuesioner');

    Route::post('/admin/list-kuesioner/{id}/update', [ListKuesionerController::class, 'update'])
        ->name('admin.update-kuesioner');

    Route::delete('/admin/list-kuesioner/{id}', [ListKuesionerController::class, 'destroy'])
        ->name('admin.delete-kuesioner');

    Route::delete('/admin/kuesioner/{id}/delete', [ListKuesionerController::class, 'deletePertanyaan'])
        ->name('admin.delete-pertanyaan');


    // =========================
    // HASIL KUESIONER
    // =========================

    Route::get('/admin/hasil-kuesioner', [HasilKuesionerController::class, 'index'])
        ->name('admin.hasil-kuesioner');

    Route::get('/admin/hasil-kuesioner/{id}', [HasilKuesionerController::class, 'show'])
        ->name('admin.detail-hasil-kuesioner');

    Route::get('/admin/grafik-kuesioner', [HasilKuesionerController::class, 'grafik'])
        ->name('admin.grafik-kuesioner');


    // =========================
    // ADMIN - KELOMPOK ML
    // =========================

    Route::get('/admin/kelompok', [KelompokController::class, 'adminIndex'])
        ->name('admin.kelompok.index');

    Route::get('/admin/kelompok/{kelas_id}', [KelompokController::class, 'show'])
        ->name('admin.kelompok.show');

    Route::post('/admin/kelompok/{kelas_id}/proses', [KelompokController::class, 'proses'])
        ->name('admin.kelompok.proses');

    Route::delete('/admin/kelompok/{kelas_id}/reset', [KelompokController::class, 'reset'])
        ->name('admin.kelompok.reset');
});


/*
|--------------------------------------------------------------------------
| DOSEN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', DosenMiddleware::class])->group(function () {

    Route::get('/dosen/dashboard', function () {
        return view('dosen.dashboard');
    })->name('dosen.dashboard');


    // =========================
    // HASIL KUESIONER
    // =========================

    Route::get('/dosen/hasil-kuesioner', [HasilKuesionerController::class, 'index'])
        ->name('dosen.hasil-kuesioner');

    Route::get('/dosen/grafik-kuesioner', [HasilKuesionerController::class, 'grafik'])
        ->name('dosen.grafik-kuesioner');


    // =========================
    // KELAS DOSEN
    // =========================

    Route::get('/dosen/lihat-kelas', [DosenKelasController::class, 'index'])
        ->name('dosen.lihat-kelas');

    Route::get('/dosen/lihat-kelas/{id_kelas}', [DosenKelasController::class, 'show'])
        ->name('dosen.detail-kelas');


    // =========================
    // DOSEN - KELOMPOK ML
    // =========================

    Route::get('/dosen/kelompok', [KelompokController::class, 'index'])
        ->name('dosen.kelompok.index');

    Route::get('/dosen/kelompok/{kelas_id}', [KelompokController::class, 'dosenShow'])
        ->name('dosen.kelompok.show');

    Route::post('/dosen/kelompok/{kelas_id}/proses', [KelompokController::class, 'proses'])
        ->name('dosen.kelompok.proses');

    Route::delete('/dosen/kelompok/{kelas_id}/reset', [KelompokController::class, 'reset'])
        ->name('dosen.kelompok.reset');
});


/*
|--------------------------------------------------------------------------
| MAHASISWA ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', MahasiswaMiddleware::class])->group(function () {

    Route::get('/mahasiswa/dashboard', function () {
        return view('mahasiswa.dashboard');
    })->name('mahasiswa.dashboard');


    // =========================
    // TES GAYA BELAJAR
    // =========================

    Route::get('/mahasiswa/tes-gaya-belajar', [TestGayaBelajarController::class, 'index'])
        ->name('mahasiswa.tes-index');

    Route::get('/mahasiswa/tes-gaya-belajar/{list_id}', [TestGayaBelajarController::class, 'show'])
        ->name('mahasiswa.tes-show');

    Route::post('/mahasiswa/tes-gaya-belajar/{list_id}/submit', [TestGayaBelajarController::class, 'submit'])
        ->name('mahasiswa.tes-submit');

    Route::get('/mahasiswa/hasil-tes/{list_id}', [TestGayaBelajarController::class, 'hasil'])
        ->name('mahasiswa.tes-hasil');


    // =========================
    // KELAS MAHASISWA
    // =========================

    Route::get('/mahasiswa/lihat-kelas', [KelasMahasiswaController::class, 'index'])
        ->name('mahasiswa.lihat-kelas');

    Route::post('/mahasiswa/lihat-kelas/join', [KelasMahasiswaController::class, 'joinKelas'])
        ->name('mahasiswa.join-kelas');

    Route::get('/mahasiswa/lihat-kelas/{id_kelas}', [KelasMahasiswaController::class, 'show'])
        ->name('mahasiswa.detail-kelas');


    // =========================
    // MAHASISWA - KELOMPOK ML
    // =========================

    Route::get('/mahasiswa/kelompok', [KelompokController::class, 'mahasiswaIndex'])
        ->name('mahasiswa.kelompok.index');

    Route::get('/mahasiswa/kelompok/{kelas_id}', [KelompokController::class, 'mahasiswaShow'])
        ->name('mahasiswa.kelompok.show');
});


/*
|--------------------------------------------------------------------------
| PROFILE ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


require __DIR__ . '/auth.php';