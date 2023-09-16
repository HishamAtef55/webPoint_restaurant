
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel="stylesheet" href="{{URL::asset('global/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('global/css/all.min.css')}}">

    <link rel="stylesheet" href="{{URL::asset('global/css/jquery-ui.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{URL::asset('menu/css/datatables.min.css')}}">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> -->

    <link rel="stylesheet" href="{{URL::asset('control/css/hc-offcanvas-nav.css')}}">
    <link rel="stylesheet" href="{{URL::asset('control/css/style.css')}}">
    <link rel="stylesheet" href="{{URL::asset('control/css/search_select.css')}}">
    <script src="{{URL::asset('global/js/jquery-3.5.1.min.js')}}"></script>
    <script src="{{URL::asset('menu/js/datatables.min.js')}}"></script>
    <script src="{{URL::asset('js/app.js') }}"></script>

</head>
<body>
    @include('layouts.app')
    @include('includes.control.header')
    @yield('content')
</main>
@include('includes.control.footer')
</div><!-- body-row END -->
        <script src="{{URL::asset('global/js/jquery-ui.min.js')}}"></script>
        <script src="{{URL::asset('global/js/popper-1.16.0.js')}}"></script>
        <script src="{{URL::asset('global/js/jquery.tabledit.min.js')}}"></script>
        <script src="{{URL::asset('control/js/jquery.ui.touch-punch.min.js')}}"></script>
        <script src="{{URL::asset('global/js/sweetalert2@10.js')}}"></script>
        <script src="{{URL::asset('global/js/bootstrap.min.js')}}"></script>

        <script src="{{URL::asset('control/js/hc-offcanvas-nav.js')}}"></script>
        <script src="{{URL::asset('control/js/main.js')}}"></script>
        <script src="{{URL::asset('control/js/search_select.js')}}"></script>

    </body>
</html>
