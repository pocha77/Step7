@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品新規登録画面</h1>
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
    <label for="product_name" class="form-label">商品名</label>
    <input type="text" name="product_name" class="form-control" id="product_name" required>
</div>
        
        <div class="mb-3">
            <label for="company_id" class="form-label">メーカー</label>
            <select name="company_id" id="company_id" class="form-control" required>
                <!-- 企業一覧の選択肢 -->
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label for="price" class="form-label">価格</label>
            <input type="number" name="price" class="form-control" id="price" required>
        </div>
        
        <div class="mb-3">
            <label for="stock" class="form-label">在庫数</label>
            <input type="number" name="stock" class="form-control" id="stock" required>
        </div>
        
        <!-- コメント欄追加 -->
        <div class="mb-3">
            <label for="comment" class="form-label">コメント</label>
            <textarea name="comment" class="form-control" id="comment" rows="4" placeholder="コメントを入力してください"></textarea>
        </div>
        
        <div class="mb-3">
            <label for="image" class="form-label">商品画像</label>
            <input type="file" name="image" class="form-control" id="image">
        </div>
        
        <!-- 登録ボタンと戻るボタン -->
        <div class="d-flex">
            <button type="submit" class="btn btn-primary me-2">登録</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
        </div>
    </form>
</div>
@endsection
