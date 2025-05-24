@extends('staff.editor.layouts.master')

@section('page-title', $newsItem->title)

@section('page-content')

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1>{{ $newsItem->title }}</h1>

                @if($newsItem->imageParagraph && $newsItem->imageParagraph->content)
                    <img src="{{ asset('storage/' . $newsItem->imageParagraph->content) }}" class="img-fluid rounded mb-4" alt="{{ $newsItem->title }}">
                    <p class="text-muted">{{ $newsItem->imageParagraph->title ?? '' }}</p>
                @endif



                <div class="container mt-3">
                    <!--記者名子-->
                    @if($newsItem->reporter->role == 0)
                        <p class="text-muted mb-3">
                            記者：{{ $newsItem->reporter->staff->name }}
                        </p>
                    @endif
                </div>

                <div class="container mt-3" style="margin-top: 15px;"></div>

                <p class="text-muted">
                    發布於：
                    @if($newsItem->created_at)
                        {{ $newsItem->created_at->format('Y-m-d H:i:s') }}
                    @else
                        尚未設定發布時間
                    @endif

                </p>




                <hr class="my-5">

                @if($relatedParagraphs->isNotEmpty())
                    @foreach($relatedParagraphs as $paragraph)
                        @if ($loop->index > 0)
                            <div class="mb-4">
                                @if($paragraph->category == 1) <!-- 圖片 -->
                                <img src="{{ asset('storage/' . $paragraph->content) }}" class="img-fluid rounded mb-2" alt="{{ $paragraph->title ?? $newsItem->title }}">
                                <p class="text-muted">{{ $paragraph->title ?? '' }}</p>

                                @elseif($paragraph->category == 2) <!-- 影片 -->
                                <div class="mb-3">
                                    <div class="ratio ratio-16x9">
                                        <iframe src="{{ $paragraph->content }}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    <p class="text-muted">{{ $paragraph->title ?? '' }}</p>
                                </div>

                                @else <!-- 文字或其他 -->
                                <p>{{ $paragraph->content ?? '' }}</p>
                                @endif
                                <p>{{ $paragraph->paragraph_text ?? '' }}</p>

                            </div>
                        @endif
                    @endforeach
                @else
                    <p>沒有相關內容。</p>
                @endif

                <div class="d-flex gap-2 mt-3">

                        <a href="{{ route('staff.editor.review') }}" class="btn btn-secondary">返回審核頁面</a>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">返回上一頁</a>


                </div>
            </div>
        </div>
    </div>
@endsection
