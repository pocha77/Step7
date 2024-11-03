<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 認証されたユーザーのみがアクセスできるProduct関連ルート
Route::resource('products', ProductController::class)->middleware('auth');

// 認証ルートの登録
Auth::routes();

// ホーム画面へのルート
Route::get('/home', [HomeController::class, 'index'])->name('home');