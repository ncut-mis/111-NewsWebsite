<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Admin;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ReportersController;


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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
//後台登入
Route::get('admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'login']);
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::group(['middleware' => 'admin.auth'], function() {
    Route::get('admin/reporter/dashboard', function () {
        return view('admin.reporter.index');
    })->name('admin.reporter.dashboard');

    Route::get('admin/editor/dashboard', function () {
        return view('admin.editor.index'); // 主編的主頁面視圖
    })->name('admin.editor.dashboard');
});

require __DIR__.'/auth.php';

