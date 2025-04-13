@extends('staff.reporter.layouts.master')

@section('page-title', '記者後台')

@section('page-content')
<div class="container-fluid px-4">
    <h1 class="mt-4">新聞管理</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">已上線</li>
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
        @foreach($news as $new)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $new->title }}</td>
                <td>已上線</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
