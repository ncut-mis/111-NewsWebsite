@extends('staff.editor.layouts.master')

@section('page-title', '主編後台')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1>
    <!-- 麵包屑導航 -->
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">全部新聞</li>
    </ol>
    <!-- 顯示所有新聞 -->
    <table class="table">
        <thead>
        <tr>
            <!-- 表格標題 -->
            <th scope="col">#</th>
            <th scope="col">標題</th>
            <th scope="col">狀態</th>
            <th scope="col">操作</th>
        </tr>
        </thead>
        <tbody>
        @php $counter = 1; @endphp
        @foreach($news as $new)
            <!-- 過濾掉狀態為 0 的新聞 -->
            @if($new->status != 0)
                <tr>
                    <!-- 顯示新聞的序號 -->
                    <th scope="row" style="width: 50px">{{ $counter++ }}</th>
                    <!-- 顯示新聞標題 -->
                    <td>{{ $new->title }}</td>
                    <!-- 根據狀態顯示對應的文字 -->
                    <td>
                        @if($new->status == 1)
                            待審核
                        @elseif($new->status == 2)
                            已上線
                        @elseif($new->status == 3)
                            已退回
                        @elseif($new->status == 4)
                            已下架
                        @endif
                    </td>
                    <td>
                        @if($new->status == 1)
                            <form action="{{ route('staff.editor.approve', $new->id) }}" method="post" style="display: inline-block;">
                                @csrf
                                @method('patch')
                                <button type="submit" class="btn btn-success btn-sm">通過</button>
                            </form>
                            <form action="{{ route('staff.editor.return', $new->id) }}" method="post" style="display: inline-block;">
                                @csrf
                                @method('patch')
                                <button type="submit" class="btn btn-danger btn-sm">退回</button>
                            </form>
                        @elseif($new->status == 2)
                            <form action="{{ route('staff.editor.unpublish', $new->id) }}" method="post" style="display: inline-block;">
                                @csrf
                                @method('patch')
                                <button type="submit" class="btn btn-warning btn-sm">下架</button>
                            </form>
                        @elseif($new->status == 4)
                            <form action="{{ route('staff.editor.republish', $new->id) }}" method="post" style="display: inline-block;">
                                @csrf
                                @method('patch')
                                <button type="submit" class="btn btn-success btn-sm">重新上架</button>
                            </form>
                            <form action="{{ route('staff.editor.return', $new->id) }}" method="post" style="display: inline-block;">
                                @csrf
                                @method('patch')
                                <button type="submit" class="btn btn-danger btn-sm">退回</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>
@endsection
