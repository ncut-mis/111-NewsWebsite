<!-- resources/views/favorites.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('收藏列表') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- 顯示收藏的新聞 -->
                    <table class="min-w-full bg-white">
                        <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">編號</th>
                            <th class="py-2 px-4 border-b">標題</th>
                            <th class="py-2 px-4 border-b">日期</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($favorites as $favorite)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $loop->iteration }}</td>
                                <td class="py-2 px-4 border-b">{{ $favorite->news->title }}</td>
                                <td class="py-2 px-4 border-b">
                                    {{ optional($favorite->news->created_at)->format('Y-m-d') ?? '無日期' }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

