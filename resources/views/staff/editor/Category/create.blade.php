@extends('staff.editor.layouts.master') // 繼承主版面

@section('page-title', 'Create article') // 設定頁面標題

@section('page-content') // 定義頁面內容
<div class="container-fluid px-4">
    <h1 class="mt-4">新增類別</h1> <!-- 顯示頁面標題 -->
    <form action="{{ route('staff.editor.categories.store') }}" method="POST"> <!-- 新增類別表單 -->
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">類別名稱</label> <!-- 類別名稱標籤 -->
            <input type="text" class="form-control" id="name" name="name" required> <!-- 類別名稱輸入框 -->
        </div>
        <button type="submit" class="btn btn-primary">新增</button> <!-- 新增按鈕 -->
    </form>
</div>
@endsection
