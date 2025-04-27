@extends('staff.editor.layouts.master')

@section('page-title', '主編後台')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1>
    <!-- 麵包屑導航 -->
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">代審核</li>
    </ol>
    <!-- 主內容 -->
    <table class="table">
        <thead>
        <tr>
            <!-- 表格標題 -->
            <th scope="col">#</th>
            <th scope="col">標題</th>
            <th scope="col">狀態</th>
            <th scope="col">功能</th>
        </tr>
        </thead>
        <tbody>
        <!-- 檢查是否有新聞資料 -->
        @if(isset($news) && $news->count() > 0)
            @foreach($news as $item)
                <tr>
                    <!-- 顯示新聞的序號 -->
                    <th scope="row" style="width: 50px">{{ $loop->iteration }}</th>
                    <!-- 顯示新聞標題 -->
                    <td>{{ $item->title }}</td>
                    <!-- 顯示新聞狀態 -->
                    <td>待審核</td>
                    <td style="width: 220px">
                        <!-- 查看新聞按鈕 -->
                        <a href="{{ route('show.new', ['id' => $item->id]) }}" class="btn btn-primary btn-sm me-1">查看</a>
                        <!-- 通過新聞按鈕 -->
                        <form action="{{ route('staff.editor.approve', $item->id) }}" method="POST" style="display: inline-block" class="me-1">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">通過</button>
                        </form>
                        <!-- 退回新聞按鈕 -->
                        <form action="{{ route('staff.editor.return', $item->id) }}" method="POST" style="display: inline-block">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger btn-sm">退回</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        @else
            <!-- 如果沒有新聞資料，顯示提示訊息 -->
            <tr>
                <td colspan="3" class="text-center">無須審核新聞。</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@endsection
