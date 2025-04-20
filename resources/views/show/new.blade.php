@extends('layouts.master')

@section('page-title', $newsItem->title)

@section('page-style')
    <link href="{{ asset('css/home-styles.css') }}" rel="stylesheet"/>
@endsection

@section('page-content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1>{{ $newsItem->title }}</h1>

                @if($newsItem->imageParagraph && $newsItem->imageParagraph->content)
                    <img src="{{ $newsItem->imageParagraph->content }}" class="img-fluid rounded mb-4" alt="新聞圖片">
                @endif

                <p class="lead">{{ $newsItem->content }}</p>
                <p class="text-muted">
                    發布於：
                    @if($newsItem->created_at)
                        {{ $newsItem->created_at->format('Y-m-d H:i:s') }}
                    @else
                        尚未設定發布時間
                    @endif
                </p>

                <hr class="my-5">

                <h2>相關內容</h2>

                @if($relatedParagraphs->isNotEmpty())
                    @foreach($relatedParagraphs as $paragraph)
                        @if ($loop->index > 0)
                            <div class="mb-4">


                                @php
                                    $isUrl = filter_var($paragraph->content, FILTER_VALIDATE_URL);
                                @endphp
                                @if($isUrl)
                                    <img src="{{ $paragraph->content }}" class="img-fluid rounded mb-2" alt="相關圖片">
                                @else
                                    <p>{{ $paragraph->content ?? '' }}</p>
                                @endif
                                <p>{{ $paragraph->paragraph_text ?? '' }}</p>
                            </div>
                        @endif
                    @endforeach
                @else
                    <p>沒有相關內容。</p>
                @endif

                <a href="{{ route('home.index') }}" class="btn btn-secondary mt-3">返回首頁</a>
            </div>
        </div>
    </div>
@endsection
