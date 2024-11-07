@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品一覧画面</h1>

    <!-- 検索フォーム -->
    <form method="GET" action="{{ route('products.index') }}" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="商品名で検索" value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="company_id" class="form-control">
                    <option value="">企業名で検索</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">検索</button>
            </div>
        </div>
    </form>

    <!-- 新規登録ボタン -->
    <a href="{{ route('products.create') }}" class="btn btn-success mb-3">新規登録</a>

    <!-- 商品一覧テーブル -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>メーカー</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if ($product->img_path)
                            <img src="{{ asset('storage/' . $product->img_path) }}" alt="商品画像" width="50">
                        @else
                            画像なし
                        @endif
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ number_format($product->price) }}円</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->company->company_name }}</td>
                    <td>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">詳細</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('本当に削除しますか？')">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
