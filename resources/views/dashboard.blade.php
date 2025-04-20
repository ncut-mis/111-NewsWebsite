@extends('layouts.master')

@section('page-title', '新聞儀表板')

@section('page-style')
    <link href="{{ asset('css/home-styles.css') }}" rel="stylesheet"/>
@endsection

@section('page-content')

    <!-- 上方功能列 -->



        <!-- 成功/錯誤訊息 -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif


    <!-- 分類按鈕列（含即時） -->
    <div class="category-bar bg-dark py-2">
        <div class="mb-4">
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <a href="{{ route('dashboard', ['category_id' => 'live']) }}"
                   class="btn {{ request('category_id') == 'live' ? 'btn-primary text-white' : 'btn-outline text-white' }}">
                    即時
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('dashboard', ['category_id' => $category->id]) }}"
                       class="btn {{ request('category_id') == $category->id ? 'btn-primary text-white' : 'btn-outline text-white' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 新聞卡片 -->
    <section class="pt-4">
        <div class="container px-lg-5">
            <div class="row gx-lg-5">
                @if(isset($news) && $news->count() > 0)
                    @foreach($news as $item)
                        <div class="col-lg-6 col-xxl-4 mb-5">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0">
                                    <div class="mb-4">
                                        @if($item->imageParagraph && $item->imageParagraph->content)
                                            <img src="{{ $item->imageParagraph->content }}" class="img-fluid rounded-3 mb-3" alt="新聞圖片">
                                        @else
                                            <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3">
                                                <i class="bi bi-newspaper"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <h2 class="fs-4 fw-bold">
                                        <a href="{{ route('show.new', ['id' => $item->id]) }}"
                                           class="text-decoration-none text-dark" target="_blank">
                                            {{ $item->title }}
                                        </a>
                                    </h2>

                                    <!-- 收藏按鈕 -->
                                    <form action="{{ route('favorite.add') }}" method="POST" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="news_id" value="{{ $item->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">加入收藏</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-center">目前沒有任何新聞。</p>
                @endif
            </div>
        </div>
    </section>

@endsection
