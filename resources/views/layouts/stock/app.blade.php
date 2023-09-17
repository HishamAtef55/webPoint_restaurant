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

    <!-- Scripts -->
    <script src="{{ asset('stock/public/js/app.js') }}" defer></script>
    <script src="{{ asset('stock/js/fontawesome.min.js') }}"></script>
    <script src="{{ asset('stock/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('stock/js/sweetalert2@10.js') }}"></script>
    <script src="{{ asset('stock/js//main.js') }}" defer></script>
    <script src="{{ asset('stock/js/select2.min.js') }}" defer></script>
    <script src="{{ asset('stock/js/datatables.min.js') }}" ></script>
    <script src="{{ asset('stock/js/vfs_fonts.js') }}" ></script>
    <script src="{{ asset('stock/js/font.js') }}" ></script>
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
        <nav class="navbar sticky-top shadow-sm px-3">
            <div class="navbar-brand">
                <a href="{{ route('costControl') }}" class="text-white">
                    Web Point
                </a>
                <button class="toggle-menu me-2">
                    <i class="fa-solid fa-bars-staggered fa-xl"></i>
                </button>
            </div>

            <div>
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link  text-white" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link  text-white" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu position-absolute bg-dark" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item text-white" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>

        <main>
            @include('layouts.stock.header')
            <div class="wrapper">
                @yield('content')
                @include('layouts.stock.footer')
            </div>
        </main>
    </div>
</body>
</html>
