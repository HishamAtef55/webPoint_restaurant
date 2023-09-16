<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @php
            if(isset($title))
            {
                echo $title;
            }
            else{
                echo TITLE;
            }
        @endphp

    </title>
    <link rel="icon" href="{{URL::asset('control/images/0440.jpg')}}" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Michroma&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{URL::asset('global/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('global/css/all.min.css')}}">

    <link rel="stylesheet" href="{{URL::asset('global/css/jquery-ui.min.css')}}" type="text/css">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> -->

    <link rel="stylesheet" href="{{URL::asset('menu/css/datatables.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('control/css/hc-offcanvas-nav.css')}}">
    <link rel="stylesheet" href="{{URL::asset('control/css/style.css')}}">
    <link rel="stylesheet" href="{{URL::asset('control/css/search_select.css')}}">
    <script src="{{URL::asset('global/js/jquery-3.5.1.min.js')}}"></script>
    <link rel="stylesheet" href="{{URL::asset('plapla/css/keyboard.css')}}">
    <link rel="stylesheet" href="{{URL::asset('plapla/css/logoin.css')}}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script src="{{URL::asset('menu/js/datatables.min.js')}}"></script>
    <script src="{{URL::asset('menu/js/vfs_fonts.js')}}"></script>
    <script src="{{URL::asset('menu/js/font.js')}}"></script>
</head>

<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="{{URL::asset('global/js/jquery-ui.min.js')}}"></script>
        <script src="{{URL::asset('global/js/jquery.tabledit.min.js')}}"></script>
        <script src="{{URL::asset('control/js/jquery.ui.touch-punch.min.js')}}"></script>

        <script src="{{URL::asset('global/js/popper-1.16.0.js')}}"></script>
        <script src="{{URL::asset('global/js/bootstrap.min.js')}}"></script>
        <script src="{{URL::asset('global/js/sweetalert2@10.js')}}"></script>
        <script src="{{URL::asset('control/js/hc-offcanvas-nav.js')}}"></script>
        <script src="{{URL::asset('control/js/main.js')}}"></script>
        <script src="{{URL::asset('control/js/search_select.js')}}"></script>
        <script src="{{URL::asset('plapla/js/keyboard.js')}}"></script>
        <script src="{{URL::asset('plapla/js/login.js')}}"></script>
</body>
</html>
