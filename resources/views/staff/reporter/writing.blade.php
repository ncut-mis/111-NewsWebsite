@extends('staff.reporter.layouts.master') 
<!-- 繼承記者後台的主版面 -->

@section('page-title', '記者後台') 
<!-- 設定頁面標題 -->

@section('page-content') 
<!-- 定義頁面內容 -->
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1> 
    <!-- 頁面主標題 -->

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">撰稿中</li>
    </ol>
    <!-- 麵包屑導航，顯示目前所在位置 -->

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-success btn-sm" href="{{ route('staff.reporter.news.create') }}">新增</a>
    </div>
    <!-- 新增新聞按鈕，連結到新增新聞的路由 -->

    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th> 
            <!-- 表格欄位：編號 -->
            <th scope="col">標題</th> 
            <!-- 表格欄位：標題 -->
            <th scope="col">狀態</th> 
            <!-- 表格欄位：狀態 -->
            <th scope="col">功能</th> 
            <!-- 表格欄位：功能 -->
        </tr>
        </thead>
        <tbody>
        @foreach($news as $new) 
        <!-- 遍歷新聞資料 -->
            <tr>
                <th scope="row">{{ $loop->iteration }}</th> 
                <!-- 顯示新聞的編號 -->
                <td>{{ $new->title }}</td> 
                <!-- 顯示新聞的標題 -->
                <td>撰稿中</td> 
                <!-- 顯示新聞的狀態 -->
                <td>
                    <a class="btn btn-primary btn-sm" href="{{ route('staff.reporter.news.edit', $new->id) }}">編輯</a>
                    <!-- 編輯按鈕，連結到編輯新聞的路由 -->

                    <form action="{{ route('staff.reporter.news.submit', $new->id) }}" method="post" style="display: inline-block">
                        @method('patch') 
                        <!-- 使用 PATCH 方法提交表單 -->
                        @csrf 
                        <!-- CSRF 保護 -->
                        <button type="submit" class="btn btn-warning btn-sm">提交</button>
                        <!-- 提交按鈕 -->
                    </form>

                    <form action="{{ route('staff.reporter.news.destroy', $new->id) }}" method="post" style="display: inline-block">
                        @method('delete') 
                        <!-- 使用 DELETE 方法提交表單 -->
                        @csrf 
                        <!-- CSRF 保護 -->
                        <button type="submit" class="btn btn-danger btn-sm">刪除</button>
                        <!-- 刪除按鈕 -->
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
<!-- 結束頁面內容區塊 -->
