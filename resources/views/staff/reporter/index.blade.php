@extends('staff.reporter.layouts.master') 
<!-- 繼承記者後台的主版面 -->

@section('page-title', '記者後台') 
<!-- 設定頁面標題為「記者後台」 -->

@section('page-content') 
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1> 
    <!-- 頁面主標題 -->

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">全部新聞</li>
    </ol>
    <!-- 麵包屑導航，顯示當前頁面位置 -->

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">標題</th>
                <th scope="col">狀態</th>
                <th scope="col">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($news as $item)
                <tr>
                    <th scope="row" style="width: 50px">{{ $loop->iteration }}</th>
                    <td>{{ $item->title }}</td>
                    <td>
                        @if($item->status == 0)
                            撰稿中
                        @elseif($item->status == 1)
                            待審核
                        @elseif($item->status == 2)
                            已上線
                        @elseif($item->status == 3)
                            被退回
                        @elseif($item->status == 4)
                            已下架
                        @endif
                    </td>
                    <td>
                        @if($item->status == 0 || $item->status == 3)
                            <a href="{{ route('staff.reporter.news.edit', $item->id) }}" class="btn btn-primary btn-sm">編輯</a>
                            <form action="{{ route('staff.reporter.news.submit', $item->id) }}" method="post" style="display: inline-block;">
                                @csrf
                                @method('patch')
                                <button type="submit" class="btn btn-warning btn-sm">提交</button>
                            </form>
                            <form action="{{ route('staff.reporter.news.destroy', $item->id) }}" method="post" style="display: inline-block;">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger btn-sm">刪除</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

