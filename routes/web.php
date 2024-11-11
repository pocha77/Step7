<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;

// 認証ルートの登録
Auth::routes();

// ホーム画面へのルート
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Product関連のルート（個別に記述し、認証を追加）
Route::get('products', [ProductController::class, 'index'])->name('products.index')->middleware('auth');
Route::get('products/create', [ProductController::class, 'create'])->name('products.create')->middleware('auth');
Route::post('products', [ProductController::class, 'store'])->name('products.store')->middleware('auth');
Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show')->middleware('auth');
Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware('auth');
Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('auth');
Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('auth');
