@extends('staff.reporter.layouts.master')

@section('page-title', '編輯新聞')

@section('page-content')
<div class="container-fluid px-4">
    <!-- 頁面標題 -->
    <h1 class="mt-4">新聞管理</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">編輯新聞</li>
    </ol>

    <!-- 標題輸入區 -->
    <div class="mb-4 p-3 border rounded bg-light">
        <form action="{{ route('staff.reporter.news.update', $news->id) }}" method="post" enctype="multipart/form-data">
            @method('patch') <!-- 使用 PATCH 方法更新資料 -->
            @csrf <!-- CSRF 保護 -->
            <div class="mb-3">
                <!-- 標題輸入框 -->
                <label for="title" class="form-label">標題</label>
                <input id="title" name="title" type="text" class="form-control" placeholder="請輸入文章標題" value="{{ $news->title }}">
            </div>
            <div class="mb-3">
                <!-- 類別選擇 -->
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
                <!-- 儲存按鈕 -->
                <button type="submit" class="btn btn-primary btn-sm">儲存</button>
            </div>
        </form>
    </div>

    <!-- 已存在的段落 -->
    <div class="p-3 border rounded bg-white mb-4">
        <h5 class="mb-3">已存在的段落</h5>
        <div id="existing-content-container">
            @if(isset($news) && $news->imageTextParagraphs)
                <!-- 迴圈顯示已存在的段落 -->
                @foreach($news->imageTextParagraphs->sortBy('order') as $paragraph)
                    <div class="mb-3" id="content-item-{{ $paragraph->id }}">
                        @if($paragraph->category == 0)
                            <!-- 文字段落 -->
                            <label for="content-{{ $paragraph->id }}" class="form-label">文字內容 (順序: {{ $paragraph->order }})</label>
                            <textarea id="content-{{ $paragraph->id }}" name="contents[{{ $paragraph->id }}][content]" class="form-control" rows="3">{{ $paragraph->content }}</textarea>
                            <input type="hidden" name="contents[{{ $paragraph->id }}][title]" value="">
                        @elseif($paragraph->category == 1 || $paragraph->category == 2)
                            <!-- 圖片或影片段落 -->
                            <label for="title-{{ $paragraph->id }}" class="form-label">標題 (順序: {{ $paragraph->order }})</label>
                            <input id="title-{{ $paragraph->id }}" name="contents[{{ $paragraph->id }}][title]" type="text" class="form-control mb-2" value="{{ $paragraph->title }}">
                            <label for="content-{{ $paragraph->id }}" class="form-label">{{ $paragraph->category == 1 ? '圖片上傳' : '影片 URL' }} </label>
                            <input id="content-{{ $paragraph->id }}" name="contents[{{ $paragraph->id }}][content_file]" type="{{ $paragraph->category == 1 ? 'file' : 'url' }}" class="form-control" value="{{ $paragraph->content }}">
                            <input type="hidden" name="contents[{{ $paragraph->id }}][existing_content]" value="{{ $paragraph->content }}">
                            @if($paragraph->category == 1)
                                <!-- 圖片預覽 -->
                                <div class="mt-2">
                                    <img id="preview-{{ $paragraph->id }}" src="{{ asset('storage/' . $paragraph->content) }}" alt="圖片預覽" style="max-width: 200px; max-height: 200px;">
                                </div>
                            @endif
                        @endif
                        <!-- 隱藏欄位 -->
                        <input type="hidden" name="contents[{{ $paragraph->id }}][category]" value="{{ $paragraph->category }}">
                        <input type="hidden" name="contents[{{ $paragraph->id }}][order]" value="{{ $paragraph->order }}">
                        <div class="mt-2">
                            <!-- 操作按鈕 -->
                            <button type="button" class="btn btn-success btn-sm update-content-btn" data-id="{{ $paragraph->id }}">更新</button>
                            <button type="button" class="btn btn-danger btn-sm remove-content-btn" data-id="{{ $paragraph->id }}">刪除</button>
                            <button type="button" class="btn btn-secondary btn-sm move-up-btn" data-id="{{ $paragraph->id }}">上移</button>
                            <button type="button" class="btn btn-secondary btn-sm move-down-btn" data-id="{{ $paragraph->id }}">下移</button>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- 無內容提示 -->
                <p>目前沒有內容。</p>
            @endif
        </div>
    </div>

    <!-- 新增的段落 -->
    <div class="p-3 border rounded bg-white">
        <h5 class="mb-3">新增的段落</h5>

        <!-- 新增內容容器 -->
        <div id="new-content-container"></div>

        <!-- 新增內容類型選擇 -->
        <div class="mb-3 mt-3">
            <select class="form-select form-select-sm" id="add-content-select">
                <option value="" selected disabled>選擇新增內容類型</option>
                <option value="0">新增文字</option>
                <option value="1">新增圖片</option>
                <option value="2">新增影片</option>
            </select>
        </div>
    </div>
</div>

<script>
    // 新增內容選擇事件
    document.getElementById('add-content-select').addEventListener('change', function () {
        const category = this.value; // 取得選擇的類型
        const container = document.getElementById('new-content-container'); // 新增內容容器
        const existingContainer = document.getElementById('existing-content-container'); // 已存在內容容器
        const order = existingContainer.children.length + container.children.length + 1; // 計算順序

        // 根據類型生成對應的 HTML
        let contentHtml = `
            <div class="mb-3" id="content-item-new-${order}">
                ${category === '0' ? `
                    <!-- 新增文字內容 -->
                    <label for="content-new-${order}" class="form-label">文字內容 (順序: ${order})</label>
                    <textarea id="content-new-${order}" name="contents[new-${order}][content]" class="form-control" rows="3"></textarea>
                    <input type="hidden" name="contents[new-${order}][title]" value="">
                ` : category === '1' ? `
                    <!-- 新增圖片內容 -->
                    <label for="title-new-${order}" class="form-label">圖片標題 (順序: ${order})</label>
                    <input id="title-new-${order}" name="contents[new-${order}][title]" type="text" class="form-control mb-2">
                    <label for="content-new-${order}" class="form-label">圖片上傳 </label>
                    <input id="content-new-${order}" name="contents[new-${order}][content]" type="file" class="form-control">
                    <div class="mt-2">
                        <img id="preview-new-${order}" src="" alt="圖片預覽" style="max-width: 200px; max-height: 200px; display: none;">
                    </div>
                ` : `
                    <!-- 新增影片內容 -->
                    <label for="title-new-${order}" class="form-label">影片標題 (順序: ${order})</label>
                    <input id="title-new-${order}" name="contents[new-${order}][title]" type="text" class="form-control mb-2">
                    <label for="content-new-${order}" class="form-label">影片 URL </label>
                    <input id="content-new-${order}" name="contents[new-${order}][content]" type="url" class="form-control">
                `}
                <input type="hidden" name="contents[new-${order}][category]" value="${category}">
                <input type="hidden" name="contents[new-${order}][order]" value="${order}">
                <div class="mt-2">
                    <!-- 儲存與刪除按鈕 -->
                    <button type="button" class="btn btn-success btn-sm save-content-btn" data-id="new-${order}">儲存</button>
                    <button type="button" class="btn btn-danger btn-sm remove-content-btn" data-id="new-${order}">刪除</button>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', contentHtml); // 插入新增的內容

        // 如果是圖片類型，綁定預覽功能
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

        attachNewContentEvents(); // 綁定新增內容的事件

        // 滾動到新增的區塊
        const newItem = document.getElementById(`content-item-new-${order}`);
        newItem.scrollIntoView({ behavior: 'smooth', block: 'start' });

        this.value = ""; // 重置選單
    });

    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function () {
            const id = this.id.includes('new') ? this.id.split('-')[2] : this.id.split('-')[1]; // 判斷是新段落還是已存在段落
            const file = this.files[0]; // 取得選擇的檔案
            if (file) {
                const reader = new FileReader(); // 建立 FileReader 物件來讀取檔案
                reader.onload = function (e) {
                    const preview = document.getElementById(`preview-${id}`); // 取得對應的預覽元素
                    if (preview) {
                        preview.src = e.target.result; // 更新圖片預覽的來源
                        preview.style.display = 'block'; // 確保圖片預覽顯示
                    } else {
                        // 如果預覽元素不存在，動態建立一個新的圖片預覽元素
                        const imgPreview = document.createElement('img');
                        imgPreview.id = `preview-${id}`; // 設定圖片預覽的 ID
                        imgPreview.src = e.target.result; // 設定圖片來源
                        imgPreview.alt = '圖片預覽'; // 設定替代文字
                        imgPreview.style = 'max-width: 200px; max-height: 200px;'; // 設定圖片大小
                        input.parentNode.appendChild(imgPreview); // 將圖片預覽元素加入到 DOM 中
                    }
                };
                reader.readAsDataURL(file); // 讀取檔案並轉換為 Data URL 格式
            }
        });
    });

    function uploadImage(input, id, newsId) {
        const file = input.files[0]; // 取得選擇的檔案
        if (!file) {
            alert('請選擇圖片檔案！'); // 如果沒有選擇檔案，顯示提示訊息
            return;
        }

        const formData = new FormData(); // 建立 FormData 物件來傳遞檔案資料
        formData.append('content_file', file); // 將檔案加入到 FormData 中
        formData.append('news_id', newsId); // 加入新聞 ID
        formData.append('category', 1); // 設定類別為圖片
        formData.append('order', order); // 設定段落順序

        // 發送檔案上傳請求到後端
        fetch(`{{ route('staff.reporter.news.imageTextParagraphs.store') }}`, {
            method: 'POST', // 使用 POST 方法發送請求
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // 加入 CSRF Token 以保護請求
            },
            body: formData // 傳遞包含內容資料的 FormData
        })
        .then(response => response.json()) // 將伺服器回應轉換為 JSON 格式
        .then(data => {
            if (data.success) {
                alert('內容已成功儲存！'); // 如果儲存成功，顯示提示訊息
                if (data.url) {
                    const preview = document.getElementById(`preview-${id}`); // 取得對應的預覽元素
                    if (preview) {
                        preview.src = data.url; // 更新圖片預覽的來源
                        preview.style.display = 'block'; // 確保圖片預覽顯示
                    }
                }
            } else {
                alert(`儲存失敗：${data.message || '請稍後再試。'}`); // 如果儲存失敗，顯示錯誤訊息
            }
        })
        .catch(error => {
            console.error('儲存失敗，錯誤訊息:', error); // 捕捉錯誤並顯示在控制台
            alert('儲存失敗，請稍後再試。'); // 顯示錯誤提示訊息
        });
    }

    function attachNewContentEvents() {
        // 綁定儲存按鈕的點擊事件
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
                    method: 'POST', // 使用 POST 方法發送請求
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // 加入 CSRF Token 以保護請求
                    },
                    body: formData // 傳遞包含內容資料的 FormData
                })
                .then(response => response.json()) // 將伺服器回應轉換為 JSON 格式
                .then(data => {
                    if (data.success) {
                        alert('內容已成功儲存！'); // 如果儲存成功，顯示提示訊息
                        if (data.url) {
                            const preview = document.getElementById(`preview-${id}`); // 取得對應的預覽元素
                            if (preview) {
                                preview.src = data.url; // 更新圖片預覽的來源
                                preview.style.display = 'block'; // 確保圖片預覽顯示
                            }
                        }
                    } else {
                        alert(`儲存失敗：${data.message || '請稍後再試。'}`); // 如果儲存失敗，顯示錯誤訊息
                    }
                })
                .catch(error => {
                    console.error('儲存失敗，錯誤訊息:', error); // 捕捉錯誤並顯示在控制台
                    alert('儲存失敗，請稍後再試。'); // 顯示錯誤提示訊息
                });
            });
        });

        // 綁定刪除按鈕的點擊事件
        document.querySelectorAll('.remove-content-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id'); // 取得段落的 ID
                const item = document.getElementById(`content-item-${id}`); // 取得對應的段落元素
                if (item) {
                    item.remove(); // 從 DOM 中移除該段落
                    updateOrder(); // 更新段落順序
                }
            });
        });
    }

    function attachExistingContentEvents() {
        // 綁定更新按鈕的點擊事件
        document.querySelectorAll('.update-content-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id'); // 取得段落的 ID
                const item = document.getElementById(`content-item-${id}`); // 取得對應的段落元素
                const title = item.querySelector(`input[name="contents[${id}][title]"]`)?.value || null; // 取得標題
                const contentInput = item.querySelector(`textarea[name="contents[${id}][content]"], input[name="contents[${id}][content_file]"]`); // 取得內容輸入框
                const content = contentInput.type === 'file' ? contentInput.files[0] : contentInput.value; // 判斷內容是檔案還是文字

                const formData = new FormData(); // 建立 FormData 物件
                formData.append('_method', 'PATCH'); // 指定 HTTP 方法為 PATCH
                formData.append('title', title); // 加入標題
                if (contentInput.type === 'file' && content) {
                    formData.append('content_file', content); // 如果是檔案，加入檔案內容
                } else {
                    formData.append('content', content); // 如果是文字，加入文字內容
                }

                // 發送更新請求到後端
                fetch(`{{ route('staff.reporter.news.imageTextParagraphs.update', '') }}/${id}`, {
                    method: 'POST', // 使用 POST 方法模擬 PATCH
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // 加入 CSRF Token
                    },
                    body: formData // 傳遞 FormData 資料
                })
                .then(response => response.json()) // 將伺服器回應轉換為 JSON 格式
                .then(data => {
                    if (data.success) {
                        alert('內容已成功更新！'); // 如果更新成功，顯示提示訊息
                        if (data.url) {
                            const preview = document.getElementById(`preview-${id}`); // 取得對應的預覽元素
                            if (preview) {
                                preview.src = data.url; // 更新圖片預覽的來源
                                preview.style.display = 'block'; // 確保圖片預覽顯示
                            }
                        }
                    } else {
                        alert('更新失敗，請稍後再試。'); // 如果更新失敗，顯示錯誤訊息
                        console.error('後端回傳錯誤:', data); // 顯示後端回傳的錯誤訊息
                    }
                })
                .catch(error => {
                    console.error('更新失敗，錯誤訊息:', error); // 捕捉錯誤並顯示在控制台
                    alert('更新失敗，請稍後再試。'); // 顯示錯誤提示訊息
                });
            });
        });

        // 綁定刪除按鈕的點擊事件
        document.querySelectorAll('.remove-content-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id'); // 取得段落的 ID
                fetch(`{{ route('staff.reporter.news.imageTextParagraphs.destroy', '') }}/${id}`, {
                    method: 'DELETE', // 使用 DELETE 方法發送請求
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // 加入 CSRF Token
                    }
                })
                .then(response => response.json()) // 將伺服器回應轉換為 JSON 格式
                .then(data => {
                    if (data.success) {
                        const item = document.getElementById(`content-item-${id}`); // 取得對應的段落元素
                        if (item) {
                            item.remove(); // 從 DOM 中移除該段落
                            updateOrder(); // 更新段落順序
                        }
                        alert('內容已刪除！'); // 顯示刪除成功訊息
                    } else {
                        alert('刪除失敗，請稍後再試。'); // 如果刪除失敗，顯示錯誤訊息
                    }
                })
                .catch(error => {
                    console.error('Error:', error); // 捕捉錯誤並顯示在控制台
                    alert('刪除失敗，請稍後再試。'); // 顯示錯誤提示訊息
                });
            });
        });

        // 綁定上移按鈕的點擊事件
        document.querySelectorAll('.move-up-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id'); // 取得段落的 ID
                const item = document.getElementById(`content-item-${id}`); // 取得對應的段落元素
                if (item && item.previousElementSibling) {
                    item.parentNode.insertBefore(item, item.previousElementSibling); // 將段落移到前一個元素之前
                    updateOrder(); // 更新段落順序
                }
            });
        });

        // 綁定下移按鈕的點擊事件
        document.querySelectorAll('.move-down-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id'); // 取得段落的 ID
                const item = document.getElementById(`content-item-${id}`); // 取得對應的段落元素
                if (item && item.nextElementSibling) {
                    item.parentNode.insertBefore(item.nextElementSibling, item); // 將段落移到下一個元素之後
                    updateOrder(); // 更新段落順序
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
