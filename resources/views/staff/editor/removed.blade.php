@extends('staff.editor.layouts.master')

@section('page-title', '主編後台')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1>
    <!-- 麵包屑導航 -->
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">已下架</li>
    </ol>
    <!-- 顯示已下架的新聞 -->
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
        @foreach($news as $new)
            <tr>
                <!-- 顯示新聞的序號 -->
                <th scope="row">{{ $loop->iteration }}</th>
                <!-- 顯示新聞標題 -->
                <td>
                    <a href="{{ route('show.new', ['id' => $new->id]) }}" class="text-decoration-none text-dark">
                        {{ $new->title }}
                    </a>
                </td>
                <!-- 顯示新聞狀態 -->
                <td>已下架</td>
                <td>
                    <div class="d-flex gap-2">
                        <!-- 重新上架按鈕 -->
                        <form action="{{ route('staff.editor.republish', $new->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">重新上架</button>
                        </form>
                        <!-- 退回按鈕 -->
                        <form action="{{ route('staff.editor.return', $new->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-warning btn-sm">退回</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
