@extends('layouts.master')

@section('page-title', 'Home')

@section('page-style')

    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('css/home-styles.css') }}" rel="stylesheet"/>


@endsection

@section('page-content')

    <!-- 分類選單區塊 -->
    <div class="category-bar bg-dark py-2">
        <div class="mb-4">
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <!-- 新增的即時按鈕 -->
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
    <!-- Page Content-->
    <section class="pt-4">
        <div class="container px-lg-5">

            <!-- Page Features-->
            <div class="row gx-lg-5">
                @if(isset($news) && $news->count() > 0)
                    @foreach($news as $item)
                        <div class="col-lg-6 col-xxl-4 mb-5">
                            <div class="card bg-light border-0 h-100">
                                <div class="card-body text-center p-4 p-lg-5 pt-0 pt-lg-0">
                                    <div class="mb-4">
                                        @if($item->imageParagraph && $item->imageParagraph->content)
                                            <img src="{{ $item->imageParagraph->content }}"
                                                 class="img-fluid rounded-3 mb-3" alt="新聞圖片">
                                        @else
                                            <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3">
                                                <i class="bi bi-newspaper"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <h2 class="fs-4 fw-bold">
                                        <a href="{{ route('show.new', ['id' => $item->id]) }}" class="text-decoration-none text-dark" >
                                            {{ $item->title }}
                                        </a>
                                    </h2>

                                  <!--  <p class="mb-0">點擊查看詳細內容。</p> -->
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
