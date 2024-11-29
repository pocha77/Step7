<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;

class PurchaseController extends Controller
{
    public function purchase(Request $request)
    {
        try {
            // 商品取得
            $product = Product::findOrFail($request->product_id);

            // 在庫確認
            if ($product->stock <= 0) {
                return response()->json(['error' => '在庫切れです'], 400);
            }

            // 在庫を1減らす
            $product->stock -= 1;
            $product->save();

            // 購入履歴を作成
            Sale::create([
                'product_id' => $product->id,
                'quantity' => 1,
            ]);

            // 購入成功レスポンス
            return response()->json(['success' => '購入が完了しました'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => '購入処理中にエラーが発生しました'], 500);
        }
    }
}

