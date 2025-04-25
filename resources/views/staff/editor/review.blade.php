@extends('staff.editor.layouts.master')

@section('page-title', '主編後台')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">代審核</li>
    </ol>
    <!-- Main Content -->
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">標題</th>
            <th scope="col">狀態</th>
            <th scope="col">功能</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($news) && $news->count() > 0)
            @foreach($news as $item)
                <tr>
                    <th scope="row" style="width: 50px">{{ $loop->iteration }}</th>
                    <td>{{ $item->title }}</td>
                    <td>待審核</td>
                    <td style="width: 150px">
                        <form action="{{ route('staff.editor.approve', $item->id) }}" method="POST" style="display: inline-block">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">通過</button>
                        </form>
                        <form action="{{ route('staff.editor.return', $item->id) }}" method="POST" style="display: inline-block">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger btn-sm">退回</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="3" class="text-center">無須審核新聞。</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@endsection
