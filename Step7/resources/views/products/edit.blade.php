@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品情報編集画面</h1>

    <!-- 商品IDを表示 -->
    <div class="mb-3">
        <label class="form-label">商品ID:</label>
        <span>{{ $product->id }}</span>
    </div>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- 商品名 -->
        <div class="mb-3">
            <label for="product_name" class="form-label">商品名</label>
            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}" required>
        </div>

        <!-- メーカー -->
        <div class="mb-3">
            <label for="company_id" class="form-label">メーカー</label>
            <select class="form-control" id="company_id" name="company_id" required>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ $product->company_id == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- 価格 -->
        <div class="mb-3">
            <label for="price" class="form-label">価格</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ number_format($product->price, 0) }}" required>
        </div>

        <!-- 在庫数 -->
        <div class="mb-3">
            <label for="stock" class="form-label">在庫数</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
        </div>

        <!-- コメント -->
        <div class="mb-3">
            <label for="comment" class="form-label">コメント</label>
            <textarea class="form-control" id="comment" name="comment" rows="3">{{ $product->comment }}</textarea>
        </div>

        <!-- 商品画像 -->
        <div class="mb-3">
            <label for="image" class="form-label">商品画像</label>
            <input type="file" class="form-control" id="image" name="image">
            @if($product->img_path)
                <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" width="100" class="mt-2">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">更新</button>
        <a href="{{ route('products.show', $product->id) }}" class="btn btn-secondary">戻る</a>
    </form>
</div>
@endsection
