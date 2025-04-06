<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Staff;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\ReportersController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CategoriesController;

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
Route::get('staff/login', [StaffAuthController::class, 'showLoginForm'])->name('staff.login');
Route::post('staff/login', [StaffAuthController::class, 'login']);
Route::post('/logout', [StaffAuthController::class, 'logout'])->name('staff.logout');

Route::group(['middleware' => 'staff.auth'], function() {
    Route::get('staff/reporter/dashboard', [NewsController::class, 'index'])->name("staff.reporter.dashboard");

    Route::get('staff/editor/dashboard', function () {
        return view('staff.editor.index'); // 主編的主頁面視圖
    })->name('staff.editor.dashboard');
});
Route::prefix('staff')->name('staff.')->group(function () {
    Route::get('reporter', [NewsController::class, 'index'])->name("reporter.index");
    Route::get('reporter/create', [NewsController::class, 'create'])->name("reporter.create");
    Route::post('reporter', [NewsController::class, 'store'])->name("reporter.store");
    Route::get('reporter/{news}/edit', [NewsController::class, 'edit'])->name("reporter.edit");
    Route::patch('reporter/{news}', [NewsController::class, 'update'])->name("reporter.update");
    Route::patch('reporter/{news}/submit', [NewsController::class, 'submit'])->name("reporter.submit");
    Route::delete('reporter/{news}', [NewsController::class, 'destroy'])->name("reporter.destroy");
});

Route::prefix('staff/editor')->name('staff.editor.')->middleware('staff.auth')->group(function () {
    Route::resource('categories', CategoriesController::class);
});

//濤
Route::get('/my-page',function(){
    return view('welcome');
});

require __DIR__.'/auth.php';

