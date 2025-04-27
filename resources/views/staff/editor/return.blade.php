@extends('staff.editor.layouts.master')

@section('page-title', '主編後台')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1>
    <!-- 麵包屑導航 -->
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">已退回</li>
    </ol>
    <!-- 顯示已退回的新聞 -->
    <table class="table">
        <thead>
        <tr>
            <!-- 表格標題 -->
            <th scope="col">#</th>
            <th scope="col">標題</th>
            <th scope="col">狀態</th>
        </tr>
        </thead>
        <tbody>
        @foreach($news as $new)
            <tr>
                <!-- 顯示新聞的序號 -->
                <th scope="row">{{ $loop->iteration }}</th>
                <!-- 顯示新聞標題 -->
                <td>{{ $new->title }}</td>
                <!-- 顯示新聞狀態 -->
                <td>已退回</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
