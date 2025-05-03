@extends('staff.editor.layouts.master') 
<!-- 繼承主版面 -->

@section('page-title', 'Edit article') 
<!-- 設定頁面標題 -->

@section('page-content') 
<!-- 定義頁面內容 -->
<div class="container-fluid px-4">
    <h1 class="mt-4">編輯類別</h1> 
    <!-- 顯示頁面標題 -->
    <form action="{{ route('staff.editor.categories.update', $category) }}" method="POST"> 
        <!-- 編輯類別表單 -->
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label for="name" class="form-label">類別名稱</label> 
            <!-- 類別名稱標籤 -->
            <input type="text" class="form-control" id="name" name="name" value="{{ $category->name }}" required> 
            <!-- 類別名稱輸入框 -->
        </div>
        <button type="submit" class="btn btn-primary">更新</button> 
        <!-- 更新按鈕 -->
    </form>
</div>
@endsection
