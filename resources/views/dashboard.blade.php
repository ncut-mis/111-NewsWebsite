<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
            <a href="{{ route('favorites.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md float-right mb-4">收藏列表</a>
        </h2>
    </x-slot>
    <!-- 顯示成功或錯誤訊息 -->
    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded-md">
            {{ session('error') }}
        </div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("登入成功") }}
                  </div>

                </div>
            <form method="GET" action="{{ route('dashboard') }}">
                <select name="category_id" onchange="this.form.submit()" class="form-control">
                    <option value="">請選擇分類</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </form>
            @if($news->isNotEmpty())
                <h3 class="mt-4 font-bold text-lg">該分類的新聞標題：</h3>

                <div class="overflow-x-auto mt-2">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left py-2 px-4 border-b">#</th>
                            <th class="text-left py-2 px-4 border-b">標題</th>
                            <th class="text-left py-2 px-4 border-b">建立時間</th>
                            <th class="text-left py-2 px-4 border-b">操作</th> <!-- 新增的表頭 -->
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($news as $index => $item)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>
                                <td class="py-2 px-4 border-b">{{ $item->title }}</td>
                                <td class="py-2 px-4 border-b">
                                    @if($item->created_at)
                                        {{ $item->created_at->format('Y-m-d') }}
                                    @else
                                        無資料
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b text-center">
                                    <form action="{{ route('favorite.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="news_id" value="{{ $item->id }}">
                                    <button class="bg-blue-500 text-white px-4 py-2 rounded">加入收藏</button> <!-- 新增按鈕 -->
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif(request('category_id'))
                <p class="text-gray-500 mt-4">此分類目前沒有新聞。</p>
            @endif
        </div>
    </div>

</x-app-layout>
