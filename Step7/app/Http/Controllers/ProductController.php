<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request) // Requestを受け取るよう変更
    {
        $query = Product::with('company');

        // 商品名検索
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 企業名検索
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $products = $query->get(); // 検索結果を取得
        $companies = Company::all(); // 企業一覧を取得

        return view('products.index', compact('products', 'companies'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',  // コメントのバリデーション
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $path = $request->file('image')
            ? $request->file('image')->store('images', 'public')
            : null;
    
        $product = Product::create([
            'name' => $request->name,
            'company_id' => $request->company_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'comment' => $request->comment, // コメントを保存
            'image_path' => $path,
        ]);
    
        // 新規商品登録画面へリダイレクト
    return redirect()->route('products.create')->with('success', '商品が登録されました');
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
            'name' => 'required',
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|string', // コメントのバリデーション
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $product->image_path = $path;
        }
    
        $product->name = $request->name;
        $product->company_id = $request->company_id;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->comment = $request->comment; // コメントを更新
        $product->save();
    
        // 更新完了メッセージと共に編集ページにリダイレクト
        return redirect()->route('products.edit', $product->id)
                         ->with('success', '商品情報が更新されました');
    }
    

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index');
    }
}
