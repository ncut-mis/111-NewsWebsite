@extends('staff.reporter.layouts.master')

@section('page-title', '編輯新聞')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">編輯新聞</li>
    </ol>

    <!-- 標題輸入區 -->
    <div class="mb-4 p-3 border rounded bg-light">
        <form action="{{ route('staff.reporter.news.update', $news->id) }}" method="post" enctype="multipart/form-data">
            @method('patch')
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">標題</label>
                <input id="title" name="title" type="text" class="form-control" placeholder="請輸入文章標題" value="{{ $news->title }}">
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">類別</label>
                <select id="category" name="category" class="form-control">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ isset($news) && $news->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary btn-sm">儲存</button>
            </div>
        </form>
    </div>
    <!-- 已存在的段落 -->
    <div class="p-3 border rounded bg-white mb-4">
        <h5 class="mb-3">已存在的段落</h5>
        <div id="existing-content-container">
            @if(isset($news) && $news->imageTextParagraphs)
                @foreach($news->imageTextParagraphs->sortBy('order') as $paragraph)
                    <div class="mb-3" id="content-item-{{ $paragraph->id }}">
                        @if($paragraph->category == 0)
                            <label for="content-{{ $paragraph->id }}" class="form-label">文字內容 (順序: {{ $paragraph->order }})</label>
                            <textarea id="content-{{ $paragraph->id }}" name="contents[{{ $paragraph->id }}][content]" class="form-control" rows="3">{{ $paragraph->content }}</textarea>
                            <input type="hidden" name="contents[{{ $paragraph->id }}][title]" value="">
                        @elseif($paragraph->category == 1 || $paragraph->category == 2)
                            <label for="title-{{ $paragraph->id }}" class="form-label">標題 (順序: {{ $paragraph->order }})</label>
                            <input id="title-{{ $paragraph->id }}" name="contents[{{ $paragraph->id }}][title]" type="text" class="form-control mb-2" value="{{ $paragraph->title }}">
                            <label for="content-{{ $paragraph->id }}" class="form-label">{{ $paragraph->category == 1 ? '圖片上傳' : '影片 URL' }} (順序: {{ $paragraph->order }})</label>
                            <input id="content-{{ $paragraph->id }}" name="contents[{{ $paragraph->id }}][content_file]" type="{{ $paragraph->category == 1 ? 'file' : 'url' }}" class="form-control" value="{{ $paragraph->content }}">
                            <input type="hidden" name="contents[{{ $paragraph->id }}][existing_content]" value="{{ $paragraph->content }}">
                            @if($paragraph->category == 1)
                                <div class="mt-2">
                                    <img id="preview-{{ $paragraph->id }}" src="{{ asset('storage/' . $paragraph->content) }}" alt="圖片預覽" style="max-width: 200px; max-height: 200px;">
                                </div>
                            @endif
                        @endif
                        <input type="hidden" name="contents[{{ $paragraph->id }}][category]" value="{{ $paragraph->category }}">
                        <input type="hidden" name="contents[{{ $paragraph->id }}][order]" value="{{ $paragraph->order }}">
                        <div class="mt-2">
                            <button type="button" class="btn btn-success btn-sm update-content-btn" data-id="{{ $paragraph->id }}">更新</button>
                            <button type="button" class="btn btn-danger btn-sm remove-content-btn" data-id="{{ $paragraph->id }}">刪除</button>
                            <button type="button" class="btn btn-secondary btn-sm move-up-btn" data-id="{{ $paragraph->id }}">上移</button>
                            <button type="button" class="btn btn-secondary btn-sm move-down-btn" data-id="{{ $paragraph->id }}">下移</button>
                        </div>
                    </div>
                @endforeach
            @else
                <p>目前沒有內容。</p>
            @endif
        </div>
    </div>

    <!-- 新增的段落 -->
    <div class="p-3 border rounded bg-white">
        <h5 class="mb-3">新增的段落</h5>

        <div id="new-content-container"></div>

        <!-- 新增按鈕 -->
        <div class="mb-3 mt-3">
            <button class="btn btn-secondary btn-sm" id="add-content-btn">新增內容</button>
            <div id="content-options" class="mt-2" style="display: none;">
                <button class="btn btn-outline-primary btn-sm" data-category="0">新增文字</button>
                <button class="btn btn-outline-secondary btn-sm" data-category="1">新增圖片</button>
                <button class="btn btn-outline-success btn-sm" data-category="2">新增影片</button>
            </div>
        </div>
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
            const container = document.getElementById('new-content-container');
            const existingContainer = document.getElementById('existing-content-container');
            const order = existingContainer.children.length + container.children.length + 1;

            let contentHtml = `
                <div class="mb-3" id="content-item-new-${order}">
                    ${category === '0' ? `
                        <label for="content-new-${order}" class="form-label">文字內容 (順序: ${order})</label>
                        <textarea id="content-new-${order}" name="contents[new-${order}][content]" class="form-control" rows="3"></textarea>
                        <input type="hidden" name="contents[new-${order}][title]" value="">
                    ` : category === '1' ? `
                        <label for="title-new-${order}" class="form-label">圖片標題 (順序: ${order})</label>
                        <input id="title-new-${order}" name="contents[new-${order}][title]" type="text" class="form-control mb-2">
                        <label for="content-new-${order}" class="form-label">圖片上傳 (順序: ${order})</label>
                        <input id="content-new-${order}" name="contents[new-${order}][content]" type="file" class="form-control">
                        <div class="mt-2">
                            <img id="preview-new-${order}" src="" alt="圖片預覽" style="max-width: 200px; max-height: 200px; display: none;">
                        </div>
                    ` : `
                        <label for="title-new-${order}" class="form-label">影片標題 (順序: ${order})</label>
                        <input id="title-new-${order}" name="contents[new-${order}][title]" type="text" class="form-control mb-2">
                        <label for="content-new-${order}" class="form-label">影片 URL (順序: ${order})</label>
                        <input id="content-new-${order}" name="contents[new-${order}][content]" type="url" class="form-control">
                    `}
                    <input type="hidden" name="contents[new-${order}][category]" value="${category}">
                    <input type="hidden" name="contents[new-${order}][order]" value="${order}">
                    <div class="mt-2">
                        <button type="button" class="btn btn-success btn-sm save-content-btn" data-id="new-${order}">儲存</button>
                        <button type="button" class="btn btn-danger btn-sm remove-content-btn" data-id="new-${order}">刪除</button>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', contentHtml);

            if (category === '1') {
                const fileInput = document.getElementById(`content-new-${order}`);
                fileInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const preview = document.getElementById(`preview-new-${order}`);
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            attachNewContentEvents();
        });
    });

    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function () {
            const id = this.id.includes('new') ? this.id.split('-')[2] : this.id.split('-')[1];
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const preview = document.getElementById(`preview-${id}`);
                    if (preview) {
                        preview.src = e.target.result; // 更新圖片預覽
                        preview.style.display = 'block'; // 確保圖片顯示
                    } else {
                        const imgPreview = document.createElement('img');
                        imgPreview.id = `preview-${id}`;
                        imgPreview.src = e.target.result;
                        imgPreview.alt = '圖片預覽';
                        imgPreview.style = 'max-width: 200px; max-height: 200px;';
                        input.parentNode.appendChild(imgPreview);
                    }
                };
                reader.readAsDataURL(file); // 預覽圖片
            }
        });
    });

    function uploadImage(input, id, newsId) {
        const file = input.files[0];
        if (!file) {
            alert('請選擇圖片檔案！');
            return;
        }

        const formData = new FormData();
        formData.append('content_file', file); // 確保檔案正確附加
        formData.append('news_id', newsId);
        formData.append('category', 1); // 類別為圖片
        formData.append('order', order);

        fetch(`{{ route('staff.reporter.news.imageTextParagraphs.store') }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('檔案上傳成功:', data.url);
            } else {
                console.error('檔案上傳失敗:', data.message);
                alert(`檔案上傳失敗：${data.message || '請稍後再試。'}`);
            }
        })
        .catch(error => {
            console.error('檔案上傳錯誤:', error);
            alert('檔案上傳失敗，請稍後再試。');
        });
    }

    function attachNewContentEvents() {
        document.querySelectorAll('.save-content-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const item = document.getElementById(`content-item-${id}`);
                const category = item.querySelector(`input[name="contents[${id}][category]"]`).value;
                const title = item.querySelector(`input[name="contents[${id}][title]"]`)?.value || null;
                const contentInput = item.querySelector(`textarea[name="contents[${id}][content]"], input[name="contents[${id}][content]"]`);
                const content = contentInput.type === 'file' ? contentInput.files[0] : contentInput.value;
                const order = item.querySelector(`input[name="contents[${id}][order]"]`).value;

                const formData = new FormData();
                formData.append('news_id', {{ $news->id }});
                formData.append('category', category);
                formData.append('title', title);
                formData.append('order', order);

                if (contentInput.type === 'file' && content) {
                    formData.append('content_file', content);
                } else {
                    formData.append('content', content);
                }

                fetch(`{{ route('staff.reporter.news.imageTextParagraphs.store') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('內容已成功儲存！');
                        if (data.url) {
                            const preview = document.getElementById(`preview-${id}`);
                            if (preview) {
                                preview.src = data.url; // 更新圖片預覽
                                preview.style.display = 'block'; // 確保圖片顯示
                            }
                        }
                    } else {
                        alert(`儲存失敗：${data.message || '請稍後再試。'}`);
                    }
                })
                .catch(error => {
                    console.error('儲存失敗，錯誤訊息:', error);
                    alert('儲存失敗，請稍後再試。');
                });
            });
        });

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
    }

    function attachExistingContentEvents() {
        document.querySelectorAll('.update-content-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const item = document.getElementById(`content-item-${id}`);
                const title = item.querySelector(`input[name="contents[${id}][title]"]`)?.value || null;
                const contentInput = item.querySelector(`textarea[name="contents[${id}][content]"], input[name="contents[${id}][content_file]"]`);
                const content = contentInput.type === 'file' ? contentInput.files[0] : contentInput.value;

                const formData = new FormData();
                formData.append('_method', 'PATCH'); // 明確指定 PATCH 方法
                formData.append('title', title);
                if (contentInput.type === 'file' && content) {
                    formData.append('content_file', content);
                } else {
                    formData.append('content', content);
                }

                fetch(`{{ route('staff.reporter.news.imageTextParagraphs.update', '') }}/${id}`, {
                    method: 'POST', // 使用 POST 方法來模擬 PATCH
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('內容已成功更新！');
                        if (data.url) {
                            const preview = document.getElementById(`preview-${id}`);
                            if (preview) {
                                preview.src = data.url; // 更新圖片預覽
                                preview.style.display = 'block'; // 確保圖片顯示
                            }
                        }
                    } else {
                        alert('更新失敗，請稍後再試。');
                        console.error('後端回傳錯誤:', data);
                    }
                })
                .catch(error => {
                    console.error('更新失敗，錯誤訊息:', error);
                    alert('更新失敗，請稍後再試。');
                });
            });
        });

        document.querySelectorAll('.remove-content-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                fetch(`{{ route('staff.reporter.news.imageTextParagraphs.destroy', '') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const item = document.getElementById(`content-item-${id}`);
                        if (item) {
                            item.remove();
                            updateOrder();
                        }
                        alert('內容已刪除！');
                    } else {
                        alert('刪除失敗，請稍後再試。');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('刪除失敗，請稍後再試。');
                });
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
        const existingContainer = document.getElementById('existing-content-container');
        const newContainer = document.getElementById('new-content-container');
        const allItems = [...existingContainer.children, ...newContainer.children];

        const updatedOrders = [];

        allItems.forEach((item, index) => {
            const order = index + 1;
            item.querySelector('.form-label').textContent = item.querySelector('.form-label').textContent.replace(/\(順序: \d+\)/, `(順序: ${order})`);
            item.querySelector('input[name$="[order]"]').value = order;

            const id = item.id.includes('new') ? null : item.id.replace('content-item-', '');
            updatedOrders.push({ id, order });
        });

        // 發送同步請求到後端
        fetch('{{ route('staff.reporter.news.imageTextParagraphs.updateOrder') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ orders: updatedOrders })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('順序同步失敗，請稍後再試。');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('順序同步失敗，請稍後再試。');
        });
    }

    // 初始化按鈕事件
    attachExistingContentEvents();
</script>
@endsection
