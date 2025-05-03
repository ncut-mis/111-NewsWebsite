@extends('staff.editor.layouts.master')

@section('page-title', '新聞')

@section('page-content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">查詢結果</h1>
        <p>關鍵字: {{ $query }}</p>

        @if($news->isEmpty())
            <p>沒有符合的新聞。</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">標題</th>
                        <th scope="col">狀態</th>
                        <th scope="col">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($news as $item)
                        <tr>
                            <th scope="row" style="width: 50px">{{ $loop->iteration }}</th>
                            <td>{{ $item->title }}</td>
                            <td>
                                @if($item->status == 0)
                                    撰稿中
                                @elseif($item->status == 1)
                                    待審核
                                @elseif($item->status == 2)
                                    已上線
                                @elseif($item->status == 3)
                                    被退回
                                @elseif($item->status == 4)
                                    已下架
                                @endif
                            </td>
                            <td>
                                @if($item->status == 1)
                                    <form action="{{ route('staff.editor.approve', $item->id) }}" method="post" style="display: inline-block;">
                                        @csrf
                                        @method('patch')
                                        <button type="submit" class="btn btn-success btn-sm">通過</button>
                                    </form>
                                    <form action="{{ route('staff.editor.return', $item->id) }}" method="post" style="display: inline-block;">
                                        @csrf
                                        @method('patch')
                                        <button type="submit" class="btn btn-danger btn-sm">退回</button>
                                    </form>
                                @elseif($item->status == 2)
                                    <form action="{{ route('staff.editor.unpublish', $item->id) }}" method="post" style="display: inline-block;">
                                        @csrf
                                        @method('patch')
                                        <button type="submit" class="btn btn-warning btn-sm">下架</button>
                                    </form>
                                @elseif($item->status == 4)
                                    <form action="{{ route('staff.editor.republish', $item->id) }}" method="post" style="display: inline-block;">
                                        @csrf
                                        @method('patch')
                                        <button type="submit" class="btn btn-success btn-sm">重新上架</button>
                                    </form>
                                    <form action="{{ route('staff.editor.return', $item->id) }}" method="post" style="display: inline-block;">
                                        @csrf
                                        @method('patch')
                                        <button type="submit" class="btn btn-danger btn-sm">退回</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
