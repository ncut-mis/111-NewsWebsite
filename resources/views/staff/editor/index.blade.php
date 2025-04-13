@extends('staff.editor.layouts.master')

@section('page-title', '主編後台')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">全部新聞</li>
    </ol>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">標題</th>
            <th scope="col">狀態</th>
        </tr>
        </thead>
        <tbody>
        @php $counter = 1; @endphp
        @foreach($news as $new)
            @if($new->status != 0)
                <tr>
                    <th scope="row" style="width: 50px">{{ $counter++ }}</th>
                    <td>{{ $new->title }}</td>
                    <td>
                        @if($new->status == 1)
                            待審核
                        @elseif($new->status == 2)
                            已上線
                        @elseif($new->status == 3)
                            已退回
                        @elseif($new->status == 4)
                            已下架
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>
@endsection
