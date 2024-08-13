<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @php
            if (isset($title)) {
                echo $title;
            } else {
                echo TITLE;
            }
        @endphp

    </title>

    <!-- Scripts -->
    <script src="{{ asset('stock/public/js/app.js') }}" defer></script>
    <script src="{{ asset('stock/js/fontawesome.min.js') }}"></script>
    <script src="{{ asset('stock/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('stock/js/sweetalert2@10.js') }}"></script>
    <script src="{{ asset('stock/js/main.js') }}" defer></script>
    <script src="{{ asset('stock/js/select2.min.js') }}" defer></script>
    <script src="{{ asset('stock/js/datatables.min.js') }}"></script>
    <script src="{{ asset('stock/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('stock/js/font.js') }}"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('stock/css/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('stock/public/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('stock/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('stock/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('stock/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">

        @include('layouts.stock.header')

        <div class="stock-wrapper">
            @yield('content')
        </div>
    </div>

</body>

</html>
