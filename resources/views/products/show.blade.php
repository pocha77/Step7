@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品情報詳細画面</h1>

    <div class="card">
        <div class="card-body">
            <!-- 商品IDを表示 -->
            <h5>商品ID: {{ $product->id }}</h5>
            
            <div style="display: flex; align-items: center;">
                <p style="margin-right: 10px;">商品画像:</p>
                @if($product->img_path)
                    <!-- img_pathを使用して商品画像を表示 -->
                    <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" width="90">
                @else
                    <p>画像なし</p>
                @endif
            </div>

            <p class="card-title">商品名: {{ $product->product_name }}</p>
            <p class="card-text">メーカー: {{ $product->company->company_name }}</p>
            <p class="card-text">価格: {{ number_format($product->price) }}円</p>
            <p class="card-text">在庫数: {{ $product->stock }}</p>
            <p class="card-text">コメント: {{ $product->comment }}</p>

            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">編集</a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
        </div>
    </div>
</div>
@endsection
