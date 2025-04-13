@extends('layouts.master')

@section('page-title', 'Home')

@section('page-style')
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('css/home-styles.css') }}" rel="stylesheet"/>
@endsection

@section('page-content')

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
                                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-4 mt-n4"><i
                                        class="bi bi-newspaper"></i></div>
                                <h2 class="fs-4 fw-bold">{{ $item->title }}</h2>
                                <p class="mb-0">點擊查看詳細內容。</p>
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
