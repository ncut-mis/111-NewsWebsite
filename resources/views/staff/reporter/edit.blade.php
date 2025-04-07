@extends('staff.reporter.layouts.master')

@section('page-title', 'Edit article')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">編輯新聞</li>
    </ol>

    <!-- 標題輸入區 -->
    <div class="mb-4 p-3 border rounded bg-light">
        <form action="{{ route('staff.reporter.news.update', $news->id) }}" method="post">
            @method('patch')
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">標題</label>
                <input id="title" name="title" type="text" class="form-control" placeholder="請輸入文章標題" value="{{ $news->title }}">
            </div>
        </form>
    </div>

    <!-- 內容區 -->
    <div class="p-3 border rounded bg-white">
        <h5 class="mb-3">內容</h5>
        <div id="content-container">
            @foreach($news->imageTextParagraphs->sortBy('order') as $paragraph)
                <div class="mb-3" id="content-item-{{ $paragraph->id }}">
                    @if($paragraph->category == 0)
                        <label for="content-{{ $paragraph->id }}" class="form-label">文字內容 (順序: {{ $paragraph->order }})</label>
                        <textarea id="content-{{ $paragraph->id }}" name="contents[{{ $paragraph->id }}][content]" class="form-control" rows="3">{{ $paragraph->content }}</textarea>
                    @elseif($paragraph->category == 1)
                        <label for="content-{{ $paragraph->id }}" class="form-label">圖片上傳 (順序: {{ $paragraph->order }})</label>
                        <input id="content-{{ $paragraph->id }}" name="contents[{{ $paragraph->id }}][content]" type="file" class="form-control">
                        <input type="hidden" name="contents[{{ $paragraph->id }}][existing_content]" value="{{ $paragraph->content }}">
                        <p class="mt-2">目前圖片: <a href="{{ $paragraph->content }}" target="_blank">檢視圖片</a></p>
                    @elseif($paragraph->category == 2)
                        <label for="content-{{ $paragraph->id }}" class="form-label">影片 URL (順序: {{ $paragraph->order }})</label>
                        <input id="content-{{ $paragraph->id }}" name="contents[{{ $paragraph->id }}][content]" type="url" class="form-control" value="{{ $paragraph->content }}">
                    @endif
                    <input type="hidden" name="contents[{{ $paragraph->id }}][category]" value="{{ $paragraph->category }}">
                    <input type="hidden" name="contents[{{ $paragraph->id }}][order]" value="{{ $paragraph->order }}">
                    <div class="mt-2">
                        <button type="button" class="btn btn-danger btn-sm remove-content-btn" data-id="{{ $paragraph->id }}">刪除</button>
                        <button type="button" class="btn btn-secondary btn-sm move-up-btn" data-id="{{ $paragraph->id }}">上移</button>
                        <button type="button" class="btn btn-secondary btn-sm move-down-btn" data-id="{{ $paragraph->id }}">下移</button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- 新增按鈕 -->
        <div class="mt-4">
            <button class="btn btn-secondary btn-sm" id="add-content-btn">新增內容</button>
            <div id="content-options" class="mt-2" style="display: none;">
                <button class="btn btn-outline-primary btn-sm" data-category="0">新增文字</button>
                <button class="btn btn-outline-secondary btn-sm" data-category="1">新增圖片</button>
                <button class="btn btn-outline-success btn-sm" data-category="2">新增影片</button>
            </div>
        </div>
    </div>

    <!-- 儲存與提交按鈕 -->
    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
        <form action="{{ route('staff.reporter.news.update', $news->id) }}" method="post" class="me-2">
            @method('patch')
            @csrf
            <button type="submit" class="btn btn-primary btn-sm">儲存</button>
        </form>
        <form action="{{ route('staff.reporter.news.submit', $news->id)}}" method="post">
            @method('patch')
            @csrf
            <button type="submit" class="btn btn-success btn-sm">提交</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('add-content-btn').addEventListener('click', function () {
        const options = document.getElementById('content-options');
        options.style.display = options.style.display === 'none' ? 'block' : 'none';
    });

    document.querySelectorAll('#content-options button').forEach(button => {
        button.addEventListener('click', function () {
            const category = this.getAttribute('data-category');
            const container = document.getElementById('content-container');
            const order = container.children.length + 1;

            let contentHtml = `
                <div class="mb-3" id="content-item-${order}">
                    ${category === '0' ? `
                        <label for="content-${order}" class="form-label">文字內容 (順序: ${order})</label>
                        <textarea id="content-${order}" name="contents[${order}][content]" class="form-control" rows="3"></textarea>
                    ` : category === '1' ? `
                        <label for="content-${order}" class="form-label">圖片上傳 (順序: ${order})</label>
                        <input id="content-${order}" name="contents[${order}][content]" type="file" class="form-control">
                    ` : `
                        <label for="content-${order}" class="form-label">影片 URL (順序: ${order})</label>
                        <input id="content-${order}" name="contents[${order}][content]" type="url" class="form-control" placeholder="請輸入影片 URL">
                    `}
                    <input type="hidden" name="contents[${order}][category]" value="${category}">
                    <input type="hidden" name="contents[${order}][order]" value="${order}">
                    <div class="mt-2">
                        <button type="button" class="btn btn-danger btn-sm remove-content-btn" data-id="${order}">刪除</button>
                        <button type="button" class="btn btn-secondary btn-sm move-up-btn" data-id="${order}">上移</button>
                        <button type="button" class="btn btn-secondary btn-sm move-down-btn" data-id="${order}">下移</button>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', contentHtml);
            attachEvents();
        });
    });

    function attachEvents() {
        document.querySelectorAll('.remove-content-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const item = document.getElementById(`content-item-${id}`);
                if (item) {
                    item.remove();
                    updateOrder();
                }
            });
        });

        document.querySelectorAll('.move-up-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const item = document.getElementById(`content-item-${id}`);
                if (item && item.previousElementSibling) {
                    item.parentNode.insertBefore(item, item.previousElementSibling);
                    updateOrder();
                }
            });
        });

        document.querySelectorAll('.move-down-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const item = document.getElementById(`content-item-${id}`);
                if (item && item.nextElementSibling) {
                    item.parentNode.insertBefore(item.nextElementSibling, item);
                    updateOrder();
                }
            });
        });
    }

    function updateOrder() {
        const container = document.getElementById('content-container');
        const items = container.children;
        Array.from(items).forEach((item, index) => {
            const order = index + 1;
            item.querySelector('.form-label').textContent = item.querySelector('.form-label').textContent.replace(/\(順序: \d+\)/, `(順序: ${order})`);
            item.querySelector('input[name$="[order]"]').value = order;
        });
    }

    // 初始化按鈕事件
    attachEvents();
</script>
@endsection
