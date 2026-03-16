<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\PpdbExportController;
use App\Http\Controllers\TelegramWebhookController;
use App\Livewire\Home;
use App\Livewire\Auth\Login;
use App\Livewire\Frontend\Profil;
use App\Livewire\Frontend\JurusanIndex;
use App\Livewire\Frontend\JurusanDetail;
use App\Livewire\Frontend\BeritaIndex;
use App\Livewire\Frontend\BeritaDetail;
use App\Livewire\Frontend\GaleriPage;
use App\Livewire\Frontend\AgendaIndex;
use App\Livewire\Frontend\PpdbFormPage;
use App\Livewire\Frontend\PpdbPage;
use App\Livewire\Frontend\PpdbDaftarUlang;
use App\Livewire\Frontend\PpdbStatus;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\ProfilSekolah;
use App\Livewire\Admin\Pegawai;
use App\Livewire\Admin\ProgramKeahlian;
use App\Livewire\Admin\Tefa;
use App\Livewire\Admin\Berita;
use App\Livewire\Admin\BeritaEditor;
use App\Livewire\Admin\Pengumuman;
use App\Livewire\Admin\Agenda;
use App\Livewire\Admin\Galeri;
use App\Livewire\Admin\Ppdb;
use App\Livewire\Admin\PpdbAnalytics;
use App\Livewire\Admin\PpdbApplicants;
use App\Livewire\Admin\PpdbReRegistration;
use App\Livewire\Admin\PpdbSettings;
use App\Livewire\Admin\PpdbTestScoring;
use App\Livewire\Admin\Settings;

Route::post('/telegram/webhook', TelegramWebhookController::class)
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('telegram.webhook');

// Frontend
Route::get('/', Home::class)->name('home');
Route::get('/profil', Profil::class)->name('profil');
Route::get('/jurusan', JurusanIndex::class)->name('jurusan.index');
Route::get('/jurusan/{slug}', JurusanDetail::class)->name('jurusan.show');
Route::get('/berita', BeritaIndex::class)->name('berita.index');
Route::get('/berita/{slug}', BeritaDetail::class)->name('berita.show');
Route::get('/galeri', GaleriPage::class)->name('galeri');
Route::get('/agenda', AgendaIndex::class)->name('agenda.index');
Route::get('/ppdb', PpdbPage::class)->name('ppdb.index');
Route::get('/ppdb/formulir', PpdbFormPage::class)->name('ppdb.form');
Route::get('/ppdb/status', PpdbStatus::class)->name('ppdb.status');
Route::get('/ppdb/daftar-ulang', PpdbDaftarUlang::class)->name('ppdb.daftar-ulang');

// Auth
Route::get('/login', Login::class)->middleware('guest')->name('login');
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->middleware('auth')->name('logout');

// Admin
Route::middleware(['auth', 'role:admin,ppdb-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/ppdb', Ppdb::class)->name('ppdb');
    Route::get('/ppdb/export', PpdbExportController::class)->name('ppdb.export');
    Route::get('/ppdb/pendaftar', PpdbApplicants::class)->name('ppdb.applicants');
    Route::get('/ppdb/penilaian-tes', PpdbTestScoring::class)->name('ppdb.tests');
    Route::get('/ppdb/verifikasi-daftar-ulang', PpdbReRegistration::class)->name('ppdb.re-registration');
    Route::get('/ppdb/pengaturan', PpdbSettings::class)->name('ppdb.settings');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/ppdb/analisa', PpdbAnalytics::class)->name('ppdb.analytics');
    Route::get('/profil-sekolah', ProfilSekolah::class)->name('profil-sekolah');
    Route::get('/pegawai', Pegawai::class)->name('pegawai');
    Route::get('/program-keahlian', ProgramKeahlian::class)->name('program-keahlian');
    Route::get('/tefa', Tefa::class)->name('tefa');
    Route::get('/berita', Berita::class)->name('berita');
    Route::get('/berita/tambah', BeritaEditor::class)->name('berita.create');
    Route::get('/berita/{berita}/edit', BeritaEditor::class)->name('berita.edit');
    Route::get('/pengumuman', Pengumuman::class)->name('pengumuman');
    Route::get('/agenda', Agenda::class)->name('agenda');
    Route::get('/galeri', Galeri::class)->name('galeri');
    Route::get('/settings', Settings::class)->name('settings');
});
