@extends('staff.editor.layouts.master')

@section('page-title', '主編後台')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">類別管理</h1>
    <a href="{{ route('staff.editor.categories.create') }}" class="btn btn-primary mb-3">新增類別</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>名稱</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>
                    <a href="{{ route('staff.editor.categories.edit', $category) }}" class="btn btn-warning btn-sm">編輯</a>
                    <form action="{{ route('staff.editor.categories.destroy', $category) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('確定要刪除嗎？')">刪除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
