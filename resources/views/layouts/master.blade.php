<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>@yield('page-title')</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}"/>
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet"/>
    @yield('page-style')
</head>
<body>

@include('layouts.shared.navbar')
@yield('page-content')
@include('layouts.shared.footer')

<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="{{ asset('js/scripts.js') }}"></script>
</body>
</html>
