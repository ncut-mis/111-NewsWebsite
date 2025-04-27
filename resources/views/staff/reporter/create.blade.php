@extends('staff.reporter.layouts.master') 
// 繼承記者後台的主版面

@section('page-title', '新增新聞') 
// 設定頁面標題為「新增新聞」

@section('page-content') 
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1> 
    // 頁面主標題

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">新增新聞</li>
        // 麵包屑導航，顯示當前頁面位置
    </ol>

    <!-- 標題與類別輸入區 -->
    <div class="mb-4 p-3 border rounded bg-light">
        <form id="news-form" action="{{ route('staff.reporter.news.saveTitleCategory') }}" method="post">
            @csrf 
            // CSRF 保護

            <div class="mb-3">
                <label for="title" class="form-label">標題</label>
                // 標題輸入框的標籤
                <input id="title" name="title" type="text" class="form-control" placeholder="請輸入文章標題" value="{{ $news->title ?? '' }}">
                // 標題輸入框，預設值為已存在的標題（若有）
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">類別</label>
                // 類別選擇框的標籤
                <select id="category" name="category" class="form-control">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ isset($news) && $news->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        // 迭代所有類別，並根據條件設定選中狀態
                    @endforeach
                </select>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary btn-sm">儲存</button>
                // 儲存按鈕
            </div>
        </form>
    </div>
</div>
@endsection
