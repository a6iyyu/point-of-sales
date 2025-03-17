<?php

use App\Http\Controllers\Barang;
use App\Http\Controllers\Kategori;
use App\Http\Controllers\Level;
use App\Http\Controllers\Supplier;
use App\Http\Controllers\User;
use App\Http\Controllers\Welcome;
use Illuminate\Support\Facades\Route;

Route::get('/', [Welcome::class, 'index']);

Route::prefix('user')->group(function () {
    Route::get('/', [User::class, 'index']);                            // menampilkan halaman awal user
    Route::post('/list', [User::class, 'list']);                        // menampilkan data user dalam bentuk json untuk datatables
    Route::get('/create', [User::class, 'create']);                     // menampilkan halaman form tambah user
    Route::post('/', [User::class, 'store']);                           // menyimpan data user baru
    Route::get('/create-ajax', [User::class, 'create_ajax']);           // Menampilkan halaman form tambah user AJAX
    Route::post('/ajax', [User::class, 'store_ajax']);                  // Menampilkan halaman form tambah user AJAX
    Route::get('/{id}', [User::class, 'show']);                         // menampilkan detail user
    Route::get('/{id}/edit', [User::class, 'edit']);                    // menampilkan halaman form edit user
    Route::put('/{id}', [User::class, 'update']);                       // menyimpan perubahan data user
    Route::get('/{id}/edit-ajax', [User::class, 'edit_ajax']);          // Menampilkan halaman form edit user AJAX
    Route::put('/{id}/update-ajax', [User::class, 'update_ajax']);      // Menampilkan halaman form edit user AJAX
    Route::get('/{id}/delete-ajax', [User::class, 'confirm_ajax']);     // Untuk tampilkan form confirm delete user AJAX
    Route::delete('/{id}/delete-ajax', [User::class, 'delete_ajax']);   // Untuk hapus data user AJAX
    Route::delete('/{id}', [User::class, 'destroy']);                   // menghapus data user
});

Route::prefix('level')->group(function () {
    Route::get('/', [Level::class, 'index']);                           // menampilkan halaman awal level
    Route::post('/list', [Level::class, 'list']);                       // menampilkan data level dalam bentuk json untuk datatables
    Route::get('/create', [Level::class, 'create']);                    // menampilkan halaman form tambah level
    Route::post('/', [Level::class, 'store']);                          // menyimpan data level baru
    Route::get('/create-ajax', [Level::class, 'create_ajax']);          // Menampilkan halaman form tambah level AJAX
    Route::post('/ajax', [Level::class, 'store_ajax']);                 // Menampilkan halaman form tambah level AJAX
    Route::get('/{id}', [Level::class, 'show']);                        // menampilkan detail level
    Route::get('/{id}/edit', [Level::class, 'edit']);                   // menampilkan halaman form edit level
    Route::put('/{id}', [Level::class, 'update']);                      // menyimpan perubahan data level
    Route::get('/{id}/edit-ajax', [Level::class, 'edit_ajax']);         // Menampilkan halaman form edit level AJAX
    Route::put('/{id}/update-ajax', [Level::class, 'update_ajax']);     // Menampilkan halaman form edit level AJAX
    Route::get('/{id}/delete-ajax', [Level::class, 'confirm_ajax']);    // Untuk tampilkan form confirm delete level AJAX
    Route::delete('/{id}/delete-ajax', [Level::class, 'delete_ajax']);  // Untuk hapus data level AJAX
    Route::delete('/{id}', [Level::class, 'destroy']);                  // menghapus data level
});

Route::prefix('kategori')->group(function () {
    Route::get('/', [Kategori::class, 'index']);                        // menampilkan halaman awal kategori 
    Route::post('/list', [Kategori::class, 'list']);                    // menampilkan data kategori dalam bentuk json untuk datatables 
    Route::get('/create', [Kategori::class, 'create']);                 // menampilkan halaman form tambah kategori 
    Route::post('/', [Kategori::class, 'store']);                       // menyimpan data kategori baru
    Route::get('/create-ajax', [Kategori::class, 'create_ajax']);       // Menampilkan halaman form tambah level AJAX
    Route::post('/ajax', [Kategori::class, 'store_ajax']);              // Menampilkan halaman form tambah level AJAX
    Route::get('/{id}', [Kategori::class, 'show']);                     // menampilkan detail kategori 
    Route::get('/{id}/edit', [Kategori::class, 'edit']);                // menampilkan halaman form edit kategori 
    Route::put('/{id}', [Kategori::class, 'update']);                   // menyimpan perubahan data kategori 
    Route::get('/{id}/edit-ajax', [Kategori::class, 'edit_ajax']);         // Menampilkan halaman form edit level AJAX
    Route::put('/{id}/update-ajax', [Kategori::class, 'update_ajax']);     // Menampilkan halaman form edit level AJAX
    Route::get('/{id}/delete-ajax', [Kategori::class, 'confirm_ajax']);    // Untuk tampilkan form confirm delete level AJAX
    Route::delete('/{id}/delete-ajax', [Kategori::class, 'delete_ajax']);  // Untuk hapus data level AJAX
    Route::delete('/{id}', [Kategori::class, 'destroy']);               // menghapus data kategori
});

Route::prefix('supplier')->group(function () {
    Route::get('/', [Supplier::class, 'index']);                            // menampilkan halaman awal supplier 
    Route::post('/list', [Supplier::class, 'list']);                        // menampilkan data supplier dalam bentuk json untuk datatables 
    Route::get('/create', [Supplier::class, 'create']);                     // menampilkan halaman form tambah supplier 
    Route::post('/', [Supplier::class, 'store']);                           // menyimpan data supplier baru 
    Route::get('/create-ajax', [Supplier::class, 'create_ajax']);           // Menampilkan halaman form tambah supplier AJAX
    Route::post('/ajax', [Supplier::class, 'store_ajax']);                  // Menampilkan halaman form tambah supplier AJAX
    Route::get('/{id}', [Supplier::class, 'show']);                         // menampilkan detail supplier 
    Route::get('/{id}/edit', [Supplier::class, 'edit']);                    // menampilkan halaman form edit supplier 
    Route::put('/{id}', [Supplier::class, 'update']);                       // menyimpan perubahan data supplier 
    Route::get('/{id}/edit-ajax', [Supplier::class, 'edit_ajax']);          // Menampilkan halaman form edit supplier AJAX
    Route::put('/{id}/update-ajax', [Supplier::class, 'update_ajax']);      // Menampilkan halaman form edit supplier AJAX
    Route::get('/{id}/delete-ajax', [Supplier::class, 'confirm_ajax']);     // Untuk tampilkan form confirm delete supplier AJAX
    Route::delete('/{id}/delete-ajax', [Supplier::class, 'delete_ajax']);   // Untuk hapus data supplier AJAX
    Route::delete('/{id}', [Supplier::class, 'destroy']);                   // menghapus data supplier
});

Route::prefix('barang')->group(function () {
    Route::get('/', [Barang::class, 'index']);          // menampilkan halaman awal barang 
    Route::post('/list', [Barang::class, 'list']);      // menampilkan data barang dalam bentuk json untuk datatables 
    Route::get('/create', [Barang::class, 'create']);   // menampilkan halaman form tambah barang 
    Route::post('/', [Barang::class, 'store']);         // menyimpan data barang baru 
    Route::get('/{id}', [Barang::class, 'show']);       // menampilkan detail barang 
    Route::get('/{id}/edit', [Barang::class, 'edit']);  // menampilkan halaman form edit barang 
    Route::put('/{id}', [Barang::class, 'update']);     // menyimpan perubahan data barang 
    Route::delete('/{id}', [Barang::class, 'destroy']); // menghapus data barang
});