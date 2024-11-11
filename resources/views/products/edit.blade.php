@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品情報編集画面</h1>

    <!-- 成功メッセージの表示 -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- エラーメッセージの表示 -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

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
            <input type="text" class="form-control @error('product_name') is-invalid @enderror" id="product_name" name="product_name" value="{{ old('product_name', $product->product_name) }}" required>
            @error('product_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- メーカー -->
        <div class="mb-3">
            <label for="company_id" class="form-label">メーカー</label>
            <select class="form-control @error('company_id') is-invalid @enderror" id="company_id" name="company_id" required>
                <option value="">選択してください</option> <!-- 追加 -->
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id', $product->company_id) == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
            @error('company_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- 価格 -->
        <div class="mb-3">
            <label for="price" class="form-label">価格</label>
            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- 在庫数 -->
        <div class="mb-3">
            <label for="stock" class="form-label">在庫数</label>
            <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
            @error('stock')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- コメント -->
        <div class="mb-3">
            <label for="comment" class="form-label">コメント</label>
            <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="3">{{ old('comment', $product->comment) }}</textarea>
            @error('comment')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- 商品画像 -->
        <div class="mb-3">
            <label for="image" class="form-label">商品画像</label>
            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if($product->img_path)
                <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" width="100" class="mt-2">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">更新</button>
        <a href="{{ route('products.show', $product->id) }}" class="btn btn-secondary">戻る</a>
    </form>
</div>
@endsection
