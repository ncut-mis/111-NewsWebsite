@extends('staff.reporter.layouts.master')

@section('page-title', '記者後台')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">撰稿中</li>
    </ol>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-success btn-sm" href="{{ route('staff.reporter.news.create') }}">新增</a>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">標題</th>
            <th scope="col">狀態</th>
            <th scope="col">功能</th>
        </tr>
        </thead>
        <tbody>
        @foreach($news as $new)
            <tr>
                <th scope="row">{{ $new->id }}</th>
                <td>{{ $new->title }}</td>
                <td>撰稿中</td>
                <td>
                    <a class="btn btn-primary btn-sm" href="{{ route('staff.reporter.news.edit', $new->id) }}">編輯</a>
                    <form action="{{ route('staff.reporter.news.destroy', $new->id) }}" method="post" style="display: inline-block">
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
