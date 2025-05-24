@extends('layouts.master')

@section('page-title', $newsItem->title)

@section('page-style')
    <link href="{{ asset('css/home-styles.css') }}" rel="stylesheet"/>

@endsection

@section('page-content')

    <!-- 分類選單區塊 -->
    <div class="category-bar bg-dark py-2">
        <div class="mb-4">
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <!-- 新增的即時按鈕 -->
                <a href="{{ route('home.index', ['category_id' => 'live']) }}"
                   class="btn {{ request('category_id') == 'live' ? 'btn-primary text-white' : 'btn-outline text-white' }}">
                    即時
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('home.index', ['category_id' => $category->id]) }}"
                       class="btn {{ request('category_id') == $category->id ? 'btn-primary text-white' : 'btn-outline text-white' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>


    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1>{{ $newsItem->title }}</h1>

                @if($newsItem->imageParagraph && $newsItem->imageParagraph->content)
                    <img src="{{ asset('storage/' . $newsItem->imageParagraph->content) }}" class="img-fluid rounded mb-4" alt="{{ $newsItem->title }}">
                    <p class="text-muted mb-3">{{ $newsItem->imageParagraph->title ?? '' }}</p>
                @endif

                <div id="pos-filter" class="pos-floating-buttons pos-toggle-panel">
                    <button id="tokenize-btn" class="btn btn-success">啟動友善功能</button>
                    <button class="pos-btn pos-v" data-pos="v" disabled>篩選動詞</button>
                    <button class="pos-btn pos-n" data-pos="n" disabled>篩選名詞</button>
                    <button class="pos-btn pos-a" data-pos="a" disabled>篩選形容詞</button>
                    <button id="toggle-spacing" >空格模式：關</button>
                    <button id="increase-font" class="pos-btn">A+</button>
                    <button id="decrease-font" class="pos-btn">A-</button>
                    <button id="reset-font" class="pos-btn">A↺</button>
                    <button id="restore-original" class="btn btn-warning" disabled>恢復原文</button>
                </div>


                <div class="container mt-3">
                    <!--記者名子-->
                    @if($newsItem->reporter->role == 0)
                        <p class="text-muted mb-3">
                            記者：{{ $newsItem->reporter->staff->name }}
                        </p>
                    @endif
                </div>

                <div class="container mt-3" style="margin-top: 15px;"></div>

                <p class="text-muted mb-3">
                    發布於：
                    @if($newsItem->created_at)
                        {{ $newsItem->created_at->format('Y-m-d H:i:s') }}
                    @else
                        尚未設定發布時間
                    @endif

                </p>




                <hr class="my-5">

                @if($relatedParagraphs->isNotEmpty())
                    @foreach($relatedParagraphs as $paragraph)
                        @if ($loop->index > 0)
                            <div class="mb-4">
                                @if($paragraph->category == 1) <!-- 圖片 -->
                                <img src="{{ asset('storage/' . $paragraph->content) }}" class="img-fluid rounded mb-2" alt="{{ $paragraph->title ?? $newsItem->title }}">
                                <p class="text-muted">{{ $paragraph->title ?? '' }}</p>

                                @elseif($paragraph->category == 2) <!-- 影片 -->
                                <div class="mb-3">
                                    <div class="ratio ratio-16x9">
                                        <iframe src="{{ $paragraph->content }}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    <p class="text-muted">{{ $paragraph->title ?? '' }}</p>
                                </div>

                                @else <!-- 文字或其他 -->
                                <p class="token-target" data-id="{{ $paragraph->id }}">
                                    {{ $paragraph->paragraph_text ?? $paragraph->content ?? '' }}
                                </p>
                                    @endif
                            </div>
                        @endif
                    @endforeach
                @else
                    <p>沒有相關內容。</p>
                @endif

                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('home.index') }}" class="btn btn-secondary">返回首頁</a>

                    <form action="{{ route('favorite.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="news_id" value="{{ $newsItem->id }}">
                        <button type="submit" class="btn btn-outline-primary">加入收藏</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
@section('page-script')
    <script>




        // posFilter 表示目前哪些詞性被顯示，初始全部關閉(未斷詞前)
        let posFilter = { v: true, n: true, a: true };//詞性開關
        let spacingEnabled = false; // 空格模式是否開啟
        let baseFontSize = 16; // 初始字體大小

        //恢復
        let isTokenized = false;
        let originalTitleHTML = '';
        let originalParagraphsHTML = {};

        // 斷詞後的資料，等斷詞後才有值
        let tokenizedData = null;

        document.addEventListener('DOMContentLoaded', function () {

            let currentFontSize = 1.0; // 初始大小為 1.0em

            const increaseFontBtn = document.getElementById('increase-font');
            const decreaseFontBtn = document.getElementById('decrease-font');
            const resetFontBtn = document.getElementById('reset-font');



            // 通用的更新樣式函數
            function updateFontSize() {
                const textElements = document.querySelectorAll('.token-target, h1');
                textElements.forEach(el => {
                    el.style.fontSize = `${currentFontSize}em`;
                });
            }
            //每次增加0.1字體大小
            increaseFontBtn.addEventListener('click', () => {
                currentFontSize += 0.1;
                updateFontSize();
            });

            //最小字體
            decreaseFontBtn.addEventListener('click', () => {
                currentFontSize = Math.max(0.5, currentFontSize - 0.1); // 最小 0.5em
                updateFontSize();
            });

            //回復原本字體大小
            resetFontBtn.addEventListener('click', () => {
                currentFontSize = 1.0;
                updateFontSize();
            });


            const tokenizeBtn = document.getElementById('tokenize-btn');
            const restoreBtn = document.getElementById('restore-original');
            const posTogglePanel = document.querySelector('.pos-toggle-panel');
            const posBtns = document.querySelectorAll('.pos-btn');


            // 預設詞性按鈕不可操作（斷詞前）
            posBtns.forEach(btn => btn.disabled = true);
            // 斷詞前，詞性按鈕面板顯示（或隱藏都可以，這邊保持顯示）
            posTogglePanel.style.display = 'flex';

            // 按下斷詞分析按鈕才開始斷詞
            tokenizeBtn.addEventListener('click', async function () {
                tokenizeBtn.disabled = true;
                tokenizeBtn.innerText = '處理中...';


                // 擷取所有斷詞目標段落
                const paragraphElements = document.querySelectorAll('.token-target');
                const paragraphs = Array.from(paragraphElements).map(p => ({
                    id: p.dataset.id,
                    text: p.innerText.trim()
                }));
                const title = @json($newsItem->title);

                try {
                    const response = await fetch('http://localhost:5000/tokenize', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ title: title, paragraphs: paragraphs }),
                    });

                    if (!response.ok) throw new Error('Failed to fetch tokenized data');

                    tokenizedData = await response.json();

                    // 儲存原始內容
                    originalTitleHTML = document.querySelector('h1').innerHTML;
                    originalParagraphsHTML = {};
                    document.querySelectorAll('.token-target').forEach(p => {
                        const id = p.dataset.id;
                        originalParagraphsHTML[id] = p.innerHTML;
                    });

                    // 斷詞後渲染詞
                    renderTokenized(tokenizedData);




                    isTokenized = true;
                    restoreBtn.disabled = false;

                    // 斷詞成功，啟用詞性按鈕，且預設全部開啟
                    posBtns.forEach(btn => {
                        btn.disabled = false;
                        btn.classList.add('active');
                    });
                    posFilter = { v: true, n: true, a: true };

                } catch (error) {
                    alert('斷詞失敗，請稍後再試');
                    console.error(error);
                } finally {
                    tokenizeBtn.disabled = false;
                    tokenizeBtn.innerText = '功能已啟用..';
                }
            });

            // 詞性按鈕點擊事件，切換對應詞性的顯示/隱藏
            posBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (!tokenizedData) return; // 沒斷詞前不動作

                    const pos = btn.dataset.pos;
                    posFilter[pos] = !posFilter[pos];
                    btn.classList.toggle('active');
                    rerenderTokens();
                });
            });

            // 將斷詞結果渲染到標題和段落中
            function renderTokenized(data) {
                const h1 = document.querySelector('h1');
                h1.innerHTML = renderTokens(data.title);

                document.querySelectorAll('.token-target').forEach(p => {
                    const id = p.dataset.id;
                    if (data.paragraphs[id]) {
                        p.innerHTML = renderTokens(data.paragraphs[id]);
                    }
                });
                rerenderTokens();  // 確保渲染完立即套用樣式與空格
            }

            restoreBtn.addEventListener('click', () => {
                if (!isTokenized) return;

                document.querySelector('h1').innerHTML = originalTitleHTML;
                document.querySelectorAll('.token-target').forEach(p => {
                    const id = p.dataset.id;
                    if (originalParagraphsHTML[id]) {
                        p.innerHTML = originalParagraphsHTML[id];
                    }
                });

                // 重置狀態
                isTokenized = false;
                tokenizedData = null;
                spacingEnabled = false;
                toggleSpacingBtn.innerText = '空格模式：關';

                posBtns.forEach(btn => {
                    btn.disabled = true;
                    btn.classList.remove('active');
                });

                currentFontSize = 1.0;
                updateFontSize();
                restoreBtn.disabled = true;
            });
            //詞語間空格
            const toggleSpacingBtn = document.getElementById('toggle-spacing');
            toggleSpacingBtn.addEventListener('click', () => {
                spacingEnabled = !spacingEnabled;
                toggleSpacingBtn.innerText = `空格模式：${spacingEnabled ? '開' : '關'}`;

                // 重新渲染斷詞內容
                renderTokenized(tokenizedData);
                rerenderTokens();
            });

            // 根據詞性回傳帶顏色且有 data-flag 的 HTML 字串
            function renderTokens(tokens) {
                return tokens.map(token => {
                    let color = 'black';
                    if (token.flag.startsWith('v')) color = 'red';
                    else if (token.flag.startsWith('n')) color = 'blue';
                    else if (token.flag.startsWith('a')) color = 'green';

                    // 每個詞用 <span> 包起來
                    return `<span data-flag="${token.flag}" style="color:${color}">${token.word}</span>`;
                }).join(spacingEnabled ? ' ' : '');
            }

            // 根據 posFilter 狀態顯示或隱藏對應詞性文字(只用 display:none，不刪除)
            function rerenderTokens() {
                const spans = document.querySelectorAll('h1 span[data-flag], .token-target span[data-flag]');
                spans.forEach(span => {
                    const prefix = span.dataset.flag.charAt(0);
                    span.style.display = '';

                    if (posFilter[prefix]) {
                        if (prefix === 'v') span.style.color = 'red';
                        else if (prefix === 'n') span.style.color = 'blue';
                        else if (prefix === 'a') span.style.color = 'green';
                        else span.style.color = 'black';

                        span.style.fontSize = '1.25em';
                        span.style.fontWeight = 'bold';
                    } else {
                        span.style.color = 'black';
                        span.style.fontSize = '';
                        span.style.fontWeight = '';
                    }
                });
                if (spacingEnabled && tokenizedData) {
                    renderTokenized(tokenizedData);
                }
            }
        });
    </script>

    <style>
        .pos-toggle-panel {
            position: fixed;
            right: 10px;
            top: 40%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 8px;
            z-index: 9999;
            background-color: #fff;
            padding: 8px;
            border-radius: 4px;
            box-shadow: 0 0 6px rgba(0,0,0,0.1);
        }

        .pos-btn {
            padding: 6px 12px;
            font-size: 20px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            user-select: none;
        }

        /* 按鈕啟用後的顏色設定 */
        .pos-btn.pos-v.active {
            background-color: #e74c3c; /* 紅色 */
            color: white;
        }

        .pos-btn.pos-n.active {
            background-color: #3498db; /* 藍色 */
            color: white;
        }

        .pos-btn.pos-a.active {
            background-color: #2ecc71; /* 綠色 */
            color: white;
        }

        .pos-btn:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }
    </style>
@endsection

