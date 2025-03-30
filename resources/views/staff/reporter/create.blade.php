@extends('staff.reporter.layouts.master')

@section('page-title', 'Create article')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">新增新聞</li>
    </ol>

    <!-- 標題輸入區 -->
    <div class="mb-4 p-3 border rounded bg-light">
        <form action="{{ route('staff.reporter.store') }}" method="post">
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
        </form>
    </div>

    <!-- 內容區 -->
    <div class="p-3 border rounded bg-white">
        <h5 class="mb-3">內容</h5>
        <div id="content-container">
            <!-- 動態新增的內容輸入框將插入於此 -->
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

    <!-- 儲存按鈕 -->
    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
        <button type="submit" class="btn btn-primary btn-sm">儲存</button>
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
