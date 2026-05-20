<?php
// ============================================================
// TAMBAHKAN baris-baris ini ke file routes/web.php yang sudah ada
// Letakkan di dalam group yang sesuai (admin / dosen / mahasiswa)
// ============================================================

use App\Http\Controllers\KelompokController;

// ── ADMIN ────────────────────────────────────────────────────────────────────
// Tambahkan di dalam: Route::middleware(['auth', AdminMiddleware::class])->group(...)

Route::get('/admin/kelompok', [KelompokController::class, 'adminIndex'])
    ->name('admin.kelompok.index');

Route::get('/admin/kelompok/{kelas_id}', [KelompokController::class, 'show'])
    ->name('admin.kelompok.show');

Route::post('/admin/kelompok/{kelas_id}/proses', [KelompokController::class, 'proses'])
    ->name('admin.kelompok.proses');

Route::delete('/admin/kelompok/{kelas_id}/reset', [KelompokController::class, 'reset'])
    ->name('admin.kelompok.reset');


// ── DOSEN ────────────────────────────────────────────────────────────────────
// Tambahkan di dalam: Route::middleware(['auth', DosenMiddleware::class])->group(...)

Route::get('/dosen/kelompok/{kelas_id}', [KelompokController::class, 'dosenShow'])
    ->name('dosen.kelompok.show');

Route::post('/dosen/kelompok/{kelas_id}/proses', [KelompokController::class, 'proses'])
    ->name('dosen.kelompok.proses');

Route::delete('/dosen/kelompok/{kelas_id}/reset', [KelompokController::class, 'reset'])
    ->name('dosen.kelompok.reset');


// ── MAHASISWA ────────────────────────────────────────────────────────────────
// Tambahkan di dalam: Route::middleware(['auth', MahasiswaMiddleware::class])->group(...)

Route::get('/mahasiswa/kelompok/{kelas_id}', [KelompokController::class, 'mahasiswaShow'])
    ->name('mahasiswa.kelompok.show');
