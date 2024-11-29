<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\PurchaseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ユーザー情報取得用ルート（認証済みの場合のみ）
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 商品一覧と詳細は認証不要に公開
Route::get('/products', [ProductController::class, 'index']); // 商品一覧取得
Route::get('/products/{product}', [ProductController::class, 'show']); // 商品詳細取得

// 商品操作と購入処理は認証を必須にする
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']); // 商品作成
    Route::put('/products/{product}', [ProductController::class, 'update']); // 商品更新
    Route::delete('/products/{product}', [ProductController::class, 'destroy']); // 商品削除

    // 購入処理エンドポイント
    Route::post('/purchase', [PurchaseController::class, 'purchase']); // 購入処理
});

// 未認証用のエラーハンドリング
Route::middleware('auth:sanctum')->get('/unauthenticated', function () {
    return response()->json(['error' => 'Unauthenticated.'], 401);
});
