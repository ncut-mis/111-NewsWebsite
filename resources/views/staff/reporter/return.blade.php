@extends('staff.reporter.layouts.master')

@section('page-title', '記者後台')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">被退回</li>
    </ol>
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
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $new->title }}</td>
                <td>被退回</td>
                <td>
                    <a class="btn btn-primary btn-sm" href="{{ route('staff.reporter.news.edit', $new->id) }}">編輯</a>
                    <form action="{{ route('staff.reporter.news.submit', $new->id) }}" method="post" style="display: inline-block">
                        @method('patch')
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">提交</button>
                    </form>
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
