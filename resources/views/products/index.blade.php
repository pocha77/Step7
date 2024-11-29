@extends('layouts.app')

@section('content')
<div class="container">
    <h1>商品一覧画面</h1>

    <!-- 成功・エラーメッセージの表示 -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- 検索フォーム -->
    <form method="GET" action="{{ route('products.index') }}" id="searchForm" class="mb-3">
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
        </div>
        <div class="row mt-3">
            <div class="col-md-2">
                <input type="number" name="price_min" class="form-control" placeholder="価格下限" value="{{ request('price_min') }}">
            </div>
            <div class="col-md-2">
                <input type="number" name="price_max" class="form-control" placeholder="価格上限" value="{{ request('price_max') }}">
            </div>
            <div class="col-md-2">
                <input type="number" name="stock_min" class="form-control" placeholder="在庫下限" value="{{ request('stock_min') }}">
            </div>
            <div class="col-md-2">
                <input type="number" name="stock_max" class="form-control" placeholder="在庫上限" value="{{ request('stock_max') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">検索</button>
            </div>
        </div>
    </form>

    <!-- 新規登録ボタン -->
    <a href="{{ route('products.create') }}" class="btn btn-success mb-3">新規登録</a>

    <!-- 商品一覧テーブル -->
    <table id="productTable" class="table tablesorter">
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
        <tbody id="productTableBody">
            @forelse($products as $product)
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
                        <form style="display:inline;" class="deleteForm">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm deleteButton" data-url="{{ route('products.destroy', $product->id) }}">削除</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">データがありません</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- 削除処理とソート初期化用スクリプト -->
<script>
    $(document).ready(function() {
        // CSRFトークンをAjaxリクエストに設定
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // tablesorterの初期化 (特定の列を除外)
        $('#productTable').tablesorter({
            headers: {
                1: { sorter: false }, // 商品画像列をソート対象外にする
                5: { sorter: false }, // メーカー列をソート対象外にする
                6: { sorter: false }  // 操作列をソート対象外にする
            }
        });

        // 検索フォームの送信を非同期処理で実行
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();

            let tableBody = $('#productTableBody');
            tableBody.html('<tr><td colspan="7" class="text-center">読み込み中...</td></tr>');

            $.ajax({
                url: $(this).attr('action'),
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    tableBody.empty();
                    if (response.products.length > 0) {
                        response.products.forEach(function(product) {
                            let row = `
                                <tr>
                                    <td>${product.id}</td>
                                    <td>${product.img_path ? `<img src="${product.img_path}" alt="商品画像" width="50">` : '画像なし'}</td>
                                    <td>${product.product_name}</td>
                                    <td>${new Intl.NumberFormat().format(product.price)}円</td>
                                    <td>${product.stock}</td>
                                    <td>${product.company_name || ''}</td>
                                    <td>
                                        <a href="/products/${product.id}" class="btn btn-info btn-sm">詳細</a>
                                        <form style="display:inline;" class="deleteForm">
                                            <button type="button" class="btn btn-danger btn-sm deleteButton" data-url="/products/${product.id}">削除</button>
                                        </form>
                                    </td>
                                </tr>
                            `;
                            tableBody.append(row);
                        });
                    } else {
                        tableBody.html('<tr><td colspan="7" class="text-center">該当する商品がありません</td></tr>');
                    }
                    // 再度tablesorterを適用
                    $('#productTable').trigger('update');
                },
                error: function(xhr, status, error) {
                    console.error('エラー:', error);
                    alert('検索に失敗しました。');
                }
            });
        });

        // 削除ボタンのクリックイベント
        $(document).on('click', '.deleteButton', function(e) {
            e.preventDefault();

            let url = $(this).data('url');
            let row = $(this).closest('tr');

            if (confirm('本当に削除しますか？')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(response) {
                        alert(response.message);
                        row.fadeOut();
                    },
                    error: function(xhr, status, error) {
                        console.error('削除に失敗しました:', error);
                        alert('削除に失敗しました。');
                    }
                });
            }
        });
    });
</script>

@endsection
