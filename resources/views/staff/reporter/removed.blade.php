@extends('staff.reporter.layouts.master') 
<!-- 繼承記者後台的主版面 -->

@section('page-title', '記者後台') 
<!-- 設定頁面標題為「記者後台」 -->

@section('page-content') 
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1> 
    <!-- 頁面主標題 -->

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">已下架</li>
    </ol>
    <!-- 麵包屑導航，顯示當前頁面位置 -->

    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">標題</th>
            <th scope="col">狀態</th>
        </tr>
        </thead>
        <!-- 表格標題列 -->
        <tbody>
        @foreach($news as $new)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <!-- 顯示新聞的序號 -->
                <td>
                    <a href="{{ route('staff.reporter.news.show', ['id' => $new->id]) }}" class="text-decoration-none text-dark">
                        {{ $new->title }}
                    </a>
                </td> 
                <!-- 顯示新聞標題 -->
                <td>已下架</td>
                <!-- 狀態固定為「已下架」 -->
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
