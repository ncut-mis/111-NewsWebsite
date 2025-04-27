@extends('staff.editor.layouts.master') // 繼承主版面

@section('page-title', '主編後台') // 設定頁面標題

@section('page-content') // 定義頁面內容
<div class="container-fluid px-4">
    <h1 class="mt-4">類別管理</h1> <!-- 顯示頁面標題 -->
    <div class="text-end">
        <a href="{{ route('staff.editor.categories.create') }}" class="btn btn-primary mb-3">新增類別</a> <!-- 新增類別按鈕 -->
    </div>
    <table class="table table-bordered"> <!-- 顯示類別列表 -->
        <thead>
            <tr>
                <th>#</th> <!-- 序號欄 -->
                <th>名稱</th> <!-- 類別名稱欄 -->
                <th>操作</th> <!-- 操作欄 -->
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category) <!-- 遍歷類別資料 -->
            <tr>
                <td>{{ $loop->iteration }}</td> <!-- 顯示序號 -->
                <td>{{ $category->name }}</td> <!-- 顯示類別名稱 -->
                <td>
                    <a href="{{ route('staff.editor.categories.edit', $category) }}" class="btn btn-warning btn-sm">編輯</a> <!-- 編輯按鈕 -->
                    <form action="{{ route('staff.editor.categories.destroy', $category) }}" method="POST" style="display:inline;"> <!-- 刪除表單 -->
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('確定要刪除嗎？')">刪除</button> <!-- 刪除按鈕 -->
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
