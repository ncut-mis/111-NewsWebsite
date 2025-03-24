@extends('admin.reporter.layouts.master')

@section('page-title', 'Create article')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">新增新聞</li>
    </ol>
    <form action="{{ route('admin.reporter.store') }}" method="post">

        @method('post')
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">標題</label>
            <input id="title" name="title" type="text" class="form-control" placeholder="請輸入文章標題">
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">類別</label>
            <select id="category" name="category" class="form-control">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-primary btn-sm">儲存</button>
        </div>
    </form>
</div>
@endsection
