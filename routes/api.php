<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AdminController;
use App\Models\KategoriBerita;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Route::get('/puki', function(){
//     return response()->json('awwe');
// })->name('api.puki');
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/berita/upload', [BeritaController::class, 'upload'])->name('api.berita.upload');
// Route::post('/berita/edit/{id_berita}', [BeritaController::class, 'update'])->name('api.berita.edit');
// Route::get('/berita/{id_berita}', [BeritaController::class, 'show'])->name('api.berita.show');
// Route::get('/accBerita/{id_berita}', [BeritaController::class, 'accBerita'])->name('api.berita.accBerita');
// Route::post('/komentar/{id_berita}/komentar', [BeritaController::class, 'addKomentarBerita'])->name('api.berita.komentar.add');
// Route::get('/komentar/{id_berita}', [BeritaController::class, 'getAllKomentarBerita'])->name('api.berita.komentar');

// // Batal Berita
// Route::get('/batal/{id_berita}', [BeritaController::class, 'batal'])->name('api.berita.batal');

// // Select All Berita
// Route::get('/getberita', [BeritaController::class, 'getAllBerita'])->name('api.berita');

// // Select Berita by Admin
// Route::get('/admin', [BeritaController::class, 'getBeritaByAdmin'])->name('api.berita.admin');

// // Select Berita by Kategori
// Route::get('/kategori/{id_kategori}', [BeritaController::class, 'getBeritaByKategori'])->name('api.berita.kategori');

// // Select Berita by Judul
// Route::get('/search/{text}', [BeritaController::class, 'searchBerita'])->name('api.berita.judul');

// // Select Berita Terpopuler
// Route::get('/terpopuler', [BeritaController::class, 'terpopuler'])->name('api.berita.populer');

// // Delete Berita
// Route::delete('/delete/{id_berita}', [BeritaController::class, 'delete'])->name('api.berita.delete');

// // Add View Berita
// Route::post('/views/{id_berita}', [BeritaController::class, 'addView'])->name('api.berita.views');



// // Route::middleware('auth.api')->group(function () {
// //     Route::post('/berita/upload', [BeritaController::class, 'upload'])->name('api.berita.upload');
// //     Route::post('/berita/edit', [BeritaController::class, 'update'])->name('api.berita.edit');
// //     Route::get('/berita/{id_berita}', [BeritaController::class, 'show'])->name('api.berita.show');
// // });




// // api untuk kategori
// Route::get('/berita/kategori/{id_berita}', [KategoriController::class, 'manageKategori'])->name('api.kategori');
// Route::get('/berita/kategori/all', [KategoriController::class, 'getAllKategori'])->name('api.kategori.all');




// Route::group(['middleware' => ['auth:api']], function () {
//     Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
//     Route::get('/me', [AuthController::class, 'me'])->name('api.me');

//     Route::apiResource('berita', BeritaController::class);
// });


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::get('admin/add/index', [AdminController::class, 'addAdmin'])->name('admin/tambah');

Route::post('login', [AuthController::class, 'login'])->name('api/login');
Route::post('register', [AuthController::class, 'register'])->name('api/register');
Route::get('komentar/{id_berita}', [BeritaController::class, 'getKomentar'])->name('api/komentar');

Route::middleware(['jwt.auth'])->group(function(){
    // Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('api/refresh-token');
    Route::post('logout', [AuthController::class, 'logout'])->name('api/logout');
    Route::get('me', [AuthController::class, 'me'])->name('api/me');
});

Route::middleware(['jwt.auth', 'roles:admin'])->group(function(){
    Route::post('berita/upload', [BeritaController::class, 'upload'])->name('api/berita/upload');
    Route::post('berita/edit/{id_berita}', [BeritaController::class, 'update'])->name('api/berita/edit');
});

Route::middleware(['jwt.auth', 'roles:user_admin'])->group(function(){
    Route::post('kategori/manage', [KategoriController::class, 'manage'])->name('api/kategori/manage');
    Route::get('kategori', [KategoriController::class, 'getAllKategori'])->name('api/kategori');
    Route::get('berita/delete/{id}', [BeritaController::class, 'deleteBerita'])->name('api/berita/delete');
    Route::get('berita/acc/{id_berita}', [BeritaController::class, 'accBerita'])->name('api/berita/acc');
    Route::get('berita/decline/{id_berita}', [BeritaController::class, 'batalBerita'])->name('api/berita/decline');
});

Route::middleware(['jwt.auth', 'roles:pembaca'])->group(function(){
    Route::post('komentar/create/{id_berita}', [BeritaController::class, 'addKomentarBerita'])->name('api/komentar/create');
});

