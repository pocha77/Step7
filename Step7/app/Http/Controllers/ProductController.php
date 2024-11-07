<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('company');

        // 商品名検索
        if ($request->filled('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%'); // カラム名を変更
        }

        // 企業名検索
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $products = $query->get();
        $companies = Company::all();

        return view('products.index', compact('products', 'companies'));
    }

    public function create()
    {
        $companies = Company::select('id', 'company_name')->distinct()->get();
        return view('products.create', compact('companies'));
    }

    public function store(Request $request)
    {
        \Log::info('storeメソッドが呼び出されました');
    
        try {
            // バリデーションルール
            $request->validate([
                'product_name' => 'required',
                'company_id' => 'required|integer',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'comment' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            // 画像アップロード処理
            $path = $request->file('image') 
                ? $request->file('image')->store('images', 'public')
                : null;
    
            // 商品の登録処理
            $product = Product::create([
                'product_name' => $request->product_name,
                'company_id' => $request->company_id,
                'price' => $request->price,
                'stock' => $request->stock,
                'comment' => $request->comment,
                'img_path' => $path,
            ]);
    
            \Log::info('商品が作成されました', ['product' => $product]);
    
            // 新規登録画面にリダイレクトし、登録成功メッセージを表示
            return redirect()->route('products.create')->with('success', '商品が正常に登録されました');
        } catch (\Exception $e) {
            // エラーログを記録し、エラーメッセージを表示
            \Log::error('エラーが発生しました: ' . $e->getMessage());
            return back()->with('error', '商品登録に失敗しました。もう一度お試しください');
        }
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
{
    $companies = Company::all();
    return view('products.edit', compact('product', 'companies'));
}

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required', // カラム名変更
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 画像アップロード処理
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $product->img_path = $path; // カラム名変更
        }

        // 各カラムの更新
        $product->product_name = $request->product_name; // カラム名変更
        $product->company_id = $request->company_id;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->comment = $request->comment;
        $product->save();

        return redirect()->route('products.edit', $product->id)
                         ->with('success', '商品情報が更新されました');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index');
    }
}
