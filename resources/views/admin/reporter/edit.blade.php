@extends('admin.reporter.layouts.master')

@section('page-title', 'Edit article')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">編輯新聞</li>
    </ol>
    <form action="{{ route('admin.reporter.update', $news->id) }}" method="post">

        @method('patch')
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">標題</label>
            <input id="title" name="title" type="text" class="form-control" placeholder="請輸入文章標題" value="{{ $news->title }}">
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-primary btn-sm">儲存</button>
        </div>
    </form>
    <form action="{{ route('admin.reporter.submit', $news->id)}}" method="post">
        @method('patch')
        @csrf
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-success btn-sm">提交</button>
        </div>
    </form>
</div>
@endsection
