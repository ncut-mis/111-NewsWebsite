@extends('staff.reporter.layouts.master')

@section('page-title', '記者後台')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">
            @if(request('status') === null)
                全部新聞
            @elseif(request('status') == 0)
                撰稿中
            @elseif(request('status') == 1)
                待審核
            @elseif(request('status') == 2)
                已上線
            @elseif(request('status') == 3)
                被退回
            @endif
        </li>
    </ol>
    <!-- Main Content -->
    <div class="alert alert-success alert-dismissible" role="alert" id="liveAlert">
        <strong>完成！</strong> 成功儲存文章
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-success btn-sm" href="{{ route('staff.reporter.create') }}">新增</a>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">標題</th>
            <th scope="col">功能</th>
        </tr>
        </thead>
        <tbody>
        @foreach($news as $new)
            <tr>
                <th scope="row" style="width: 50px">{{ $new->id }}</th>
                <td>{{ $new->title }}</td>
                <td style="width: 150px">
                    <a class="btn btn-primary btn-sm" href="{{ route('staff.reporter.edit', $new->id) }}">編輯</a>
                    <form action="{{ route('staff.reporter.destroy', $new->id) }}" method="post" style="display: inline-block">
                        @method('delete')
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">刪除</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
