@extends('staff.reporter.layouts.master') 
// 繼承記者後台的主版面

@section('page-title', '記者後台') 
// 設定頁面標題

@section('page-content') 
// 定義頁面內容
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1> 
    // 頁面主標題

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">審核中</li>
        // 麵包屑導航，顯示目前所在位置
    </ol>

    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th> 
            // 表格欄位：編號
            <th scope="col">標題</th> 
            // 表格欄位：標題
            <th scope="col">狀態</th> 
            // 表格欄位：狀態
        </tr>
        </thead>
        <tbody>
        @foreach($news as $new) 
        // 遍歷新聞資料
            <tr>
                <th scope="row">{{ $loop->iteration }}</th> 
                // 顯示新聞的編號
                <td>{{ $new->title }}</td> 
                // 顯示新聞的標題
                <td>待審核</td> 
                // 顯示新聞的狀態
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
// 結束頁面內容區塊
