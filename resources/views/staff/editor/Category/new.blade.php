@extends('layouts.master') // 繼承主版面

@section('page-title', $newsItem->title) // 設定頁面標題為新聞項目的標題

@section('page-style') // 引入頁面專屬的樣式
    <link href="{{ asset('css/home-styles.css') }}" rel="stylesheet"/>
@endsection

@section('page-content') // 定義頁面內容
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1>{{ $newsItem->title }}</h1> <!-- 顯示新聞標題 -->

                @if($newsItem->imageParagraph && $newsItem->imageParagraph->content) <!-- 如果有圖片段落，顯示圖片 -->
                    <img src="{{ asset('storage/' . $newsItem->imageParagraph->content) }}" class="img-fluid rounded mb-4" alt="{{ $newsItem->title }}">
                    <p class="text-muted">{{ $newsItem->imageParagraph->title ?? '' }}</p> <!-- 顯示圖片標題 -->
                @endif

                <p class="lead">{{ $newsItem->content }}</p> <!-- 顯示新聞內容 -->
                <p class="text-muted">
                    發布於：
                    @if($newsItem->created_at) <!-- 如果有發布時間，顯示格式化的時間 -->
                        {{ $newsItem->created_at->format('Y-m-d H:i:s') }}
                    @else
                        尚未設定發布時間 <!-- 如果沒有發布時間，顯示提示文字 -->
                    @endif
                </p>

                <hr class="my-5"> <!-- 分隔線 -->

                @if($relatedParagraphs->isNotEmpty()) <!-- 如果有相關段落，顯示相關內容 -->
                    @foreach($relatedParagraphs as $paragraph)
                        @if ($loop->index > 0) <!-- 跳過第一段 -->
                            <div class="mb-4">
                                @if($paragraph->category == 1) <!-- 僅當 category 為 1 時顯示圖片 -->
                                <img src="{{ asset('storage/' . $paragraph->content) }}" class="img-fluid rounded mb-2" alt="{{ $paragraph->title ?? $newsItem->title }}">
                                <p class="text-muted">{{ $paragraph->title ?? '' }}</p> <!-- 顯示段落標題 -->
                                @else
                                    <p>{{ $paragraph->content ?? '' }}</p> <!-- 顯示段落內容 -->
                                @endif
                                <p>{{ $paragraph->paragraph_text ?? '' }}</p> <!-- 顯示段落文字 -->
                            </div>
                        @endif
                    @endforeach
                @else
                    <p>沒有相關內容。</p> <!-- 如果沒有相關段落，顯示提示文字 -->
                @endif

                <div class="d-flex gap-2 mt-3"> <!-- 動作按鈕 -->
                    <a href="{{ route('home.index') }}" class="btn btn-secondary">返回首頁</a> <!-- 返回首頁按鈕 -->

                    <form action="{{ route('favorite.add') }}" method="POST"> <!-- 加入收藏表單 -->
                        @csrf
                        <input type="hidden" name="news_id" value="{{ $newsItem->id }}"> <!-- 隱藏欄位，傳遞新聞 ID -->
                        <button type="submit" class="btn btn-outline-primary">加入收藏</button> <!-- 提交按鈕 -->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
