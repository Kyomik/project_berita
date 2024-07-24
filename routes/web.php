<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DashboardController;
use Termwind\Components\Raw;

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


//Umum
Route::get('/', [BeritaController::class, 'index'])->name('/');
Route::get('/contact', function(){
	return view("contact");
})->name('contact');
Route::get('/about', function(){

})->name('about');
Route::get('/kategori/{nama_kategori}', [BeritaController::class, 'beritaByKategoriTampilan'])->name('kategori');
Route::get('/search', [BeritaController::class, 'beritaBySearchTampilan'])->name('search');
Route::get('berita/{id}', [BeritaController::class, 'preview'])->name('berita/show');

Route::middleware(['jwt.auth', 'roles:admin,user_admin'])->group(function(){
    Route::get('admin/berita/draft/{token}', [BeritaController::class, 'draftTampilan'])->name('admin/berita/draft');
    Route::get('berita/{id}/{token}', [BeritaController::class, 'preview'])->name('admin/berita/show');
    Route::get('/admin/dashboard/{token}', [DashboardController::class, 'index'])->name('admin/dashboard');
});

Route::middleware(['jwt.auth', 'roles:admin'])->group(function () {
    Route::get('admin/berita/upload/{token}', [BeritaController::class, 'uploadTampilan'])->name('admin/berita/upload');
    Route::get('/admin/berita/publish/{token}', [BeritaController::class, 'publishTampilan'])->name('admin/berita/publish');
    Route::get('/admin/berita/{id}/{token}', [BeritaController::class, 'editTampilan'])->name('admin/berita/edit');
});

Route::middleware(['jwt.auth', 'roles:user_admin'])->group(function () {
    Route::post('admin/add/index', [AdminController::class, 'addAdmin'])->name('admin/tambah');
    Route::get('admin/kategori/manage/{token}', [KategoriController::class, 'index'])->name('admin/kategori/manage');
    Route::get('admin/index/{token}', [AdminController::class, 'index'])->name('admin.index');
    Route::get('admin/add/index/{token}', [AdminController::class, 'showAddForm'])->name('admin/tambah');
    Route::get('admin/edit/{id}/{token}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::post('admin/edit/{id}', [AdminController::class, 'update'])->name('admin/update');
    Route::delete('admin/delete', [AdminController::class, 'destroy'])->name('admin.destroy');
    Route::put('admin/update/{id}', [AdminController::class, 'update'])->name('admin/update');
    Route::get('/admin/kategori/{nama_kategori}/{token}', [BeritaController::class, 'beritaByKategoriTampilan'])->name('admin/kategori');
    Route::get('/admin/search/{judul_berita}/{token}', [BeritaController::class, 'beritaBySearchTampilan'])->name('admin/search');
});
