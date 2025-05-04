@extends('layouts.master')

@section('page-title', '我的收藏')

@section('page-style')
    <link href="{{ asset('css/home-styles.css') }}" rel="stylesheet"/>
@endsection

@section('page-content')
    <!-- 分類按鈕列 -->
    <div class="category-bar bg-dark py-2">
        <div class="mb-4">
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <a href="{{ route('home.index', ['category_id' => 'live']) }}"
                   class="btn {{ request('category_id') == 'live' ? 'btn-primary text-white' : 'btn-outline text-white' }}">
                    即時
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('home.index', ['category_id' => $category->id]) }}"
                       class="btn {{ request('category_id') == $category->id ? 'btn-primary text-white' : 'btn-outline text-white' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <section class="pt-4">
        <div class="container px-lg-5">
            <h2 class="text-center mb-4">我的收藏</h2>

            <div class="row gx-lg-5">
                @forelse($favorites as $favorite)
                    <div class="col-lg-6 col-xxl-4 mb-5">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0">
                                <div class="mb-4">
                                    @if($favorite->news->imageParagraph && $favorite->news->imageParagraph->content)
                                        <img src="{{ asset('storage/' . $favorite->news->imageParagraph->content) }}" class="img-fluid rounded-3 mb-3" alt="新聞圖片">
                                    @else
                                        <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3">
                                            <i class="bi bi-newspaper"></i>
                                        </div>
                                    @endif
                                </div>
                                <h2 class="fs-4 fw-bold">
                                    <a href="{{ route('show.new', ['id' => $favorite->news->id]) }}" class="text-decoration-none text-dark">
                                        {{ $favorite->news->title }}
                                    </a>
                                </h2>

                                <p class="text-muted small mb-0">
                                    發布於：{{ $favorite->news->created_at ? $favorite->news->created_at->format('Y-m-d H:i:s') : '尚未設定發布時間' }}
                                </p>
                                @if($favorite->news->reporter->role == 0)
                                    <p class="text-muted mb-0">記者：{{ $favorite->news->reporter->staff->name }}</p>
                                @endif

                                <!-- 取消收藏按鈕 -->
                                <form action="{{ route('favorite.remove') }}" method="POST" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="news_id" value="{{ $favorite->news->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">取消收藏</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">你還沒有收藏任何新聞。</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
