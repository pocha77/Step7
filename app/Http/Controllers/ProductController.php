<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest; // バリデーション用のリクエストをインポート

class ProductController extends Controller {
    
    public function index(Request $request) {
        $query = Product::with('company');
    
        // 商品名検索
        if ($request->filled('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }
    
        // 企業名検索
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
    
        // 価格範囲検索
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
    
        // 在庫範囲検索
        if ($request->filled('stock_min')) {
            $query->where('stock', '>=', $request->stock_min);
        }
        if ($request->filled('stock_max')) {
            $query->where('stock', '<=', $request->stock_max);
        }
    
        // 非同期リクエストに対するJSONレスポンス
        if ($request->ajax()) {
            $products = $query->get()->map(function ($product) {
                $product->img_path = $product->img_path ? asset('storage/' . $product->img_path) : null;
                $product->company_name = $product->company->company_name ?? null; // 関連データを明示的に返す
                return $product;
            });
    
            return response()->json([
                'products' => $products
            ]);
        }
    
        // 通常の画面表示
        $products = $query->get();
        $companies = Company::all();
    
        return view('products.index', compact('products', 'companies'));
    }
    
    
    public function create() {
        $companies = Company::select('id', 'company_name')->distinct()->get();
        return view('products.create', compact('companies'));
    }

    public function store(ProductRequest $request) {
        \Log::info('storeメソッドが呼び出されました');
    
        try {
            $path = $request->file('image') 
                ? $request->file('image')->store('images', 'public')
                : null;
    
            $product = Product::create([
                'product_name' => $request->product_name,
                'company_id' => $request->company_id,
                'price' => $request->price,
                'stock' => $request->stock,
                'comment' => $request->comment,
                'img_path' => $path,
            ]);
    
            \Log::info('商品が作成されました', ['product' => $product]);
    
            return redirect()->route('products.create')->with('success', '商品が正常に登録されました');
        } catch (\Exception $e) {
            \Log::error('エラーが発生しました: ' . $e->getMessage());
            return back()->with('error', '商品登録に失敗しました。もう一度お試しください');
        }
    }

    public function show(Product $product) {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product) {
        $companies = Company::all();
        return view('products.edit', compact('product', 'companies'));
    }

    public function update(ProductRequest $request, Product $product) {
        try {
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images', 'public');
                $product->img_path = $path;
            }

            $product->product_name = $request->product_name;
            $product->company_id = $request->company_id;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->comment = $request->comment;
            $product->save();

            return redirect()->route('products.edit', $product->id)
                             ->with('success', '商品情報が更新されました');
        } catch (\Exception $e) {
            \Log::error('更新中にエラーが発生しました: ' . $e->getMessage());
            return back()->with('error', '商品情報の更新に失敗しました');
        }
    }

    public function destroy(Product $product) {
        try {
            $product->delete();
    
            // 非同期リクエストへの対応
            if (request()->ajax()) {
                return response()->json(['message' => '商品が削除されました。'], 200);
            }
    
            // 通常リクエストへの対応
            return redirect()->route('products.index')->with('success', '商品が削除されました');
        } catch (\Exception $e) {
            \Log::error('削除中にエラーが発生しました: ' . $e->getMessage());
    
            // 非同期リクエストへの対応
            if (request()->ajax()) {
                return response()->json(['error' => '商品の削除に失敗しました。'], 500);
            }
    
            // 通常リクエストへの対応
            return back()->with('error', '商品の削除に失敗しました');
        }
    }
    
}
