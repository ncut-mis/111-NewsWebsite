<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Staff;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\ReportersController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\EditorsController; 
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageTextParagraphsController; // 確保已正確引入控制器


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

Route::get('/', [HomeController::class, 'index'])->name('home.index');

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [CategoriesController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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

Route::prefix('staff/editor')->name('staff.editor.')->middleware('staff.auth')->group(function () {
    Route::get('dashboard', [EditorsController::class, 'index'])->name('dashboard'); // 確保路由指向正確的控制器
    Route::resource('categories', CategoriesController::class);
    Route::patch('news/{news}/approve', [NewsController::class, 'approve'])->name('approve'); // 新增審核路由
});

Route::prefix('staff/reporter/news')->name('staff.reporter.news.')->group(function () {
    Route::get('writing', [NewsController::class, 'writing'])->name('writing');
    Route::get('review', [NewsController::class, 'review'])->name('review');
    Route::get('published', [NewsController::class, 'published'])->name('published');
    Route::get('return', [NewsController::class, 'return'])->name('return');
    Route::get('removed', [NewsController::class, 'removed'])->name('removed');
    Route::get('/', [NewsController::class, 'index'])->name("index");
    Route::get('create/{news_id?}', [NewsController::class, 'create'])->name("create"); // 支援 news_id 傳遞
    Route::post('/', [NewsController::class, 'store'])->name("store");
    Route::get('{news}/edit', [NewsController::class, 'edit'])->name("edit");
    Route::patch('{news}', [NewsController::class, 'update'])->name("update");
    Route::delete('{news}', [NewsController::class, 'destroy'])->name("destroy");
    Route::post('save-title-category', [NewsController::class, 'saveTitleCategory'])->name('saveTitleCategory');
    Route::get('{news}/content', [ImageTextParagraphsController::class, 'index'])->name("content");
    Route::patch('{news}/content', [ImageTextParagraphsController::class, 'update'])->name('imageTextParagraphs.update'); // 修正路由名稱
    Route::post('content/store', [ImageTextParagraphsController::class, 'store'])->name('imageTextParagraphs.store');
    Route::delete('content/{id}', [ImageTextParagraphsController::class, 'destroy'])->name('imageTextParagraphs.destroy');
    Route::post('content/update-order', [ImageTextParagraphsController::class, 'updateOrder'])->name('imageTextParagraphs.updateOrder');
    Route::patch('content/{id}', [ImageTextParagraphsController::class, 'update'])->name('imageTextParagraphs.update');
});

Route::post('/favorite', [NewsController::class, 'addFavorite'])->middleware('auth')->name('favorite.add');
Route::get('/favorites', [NewsController::class, 'favoriteList'])->middleware('auth')->name('favorites.index');
//濤
Route::get('/my-page',function(){
    return view('welcome');
});

require __DIR__.'/auth.php';

