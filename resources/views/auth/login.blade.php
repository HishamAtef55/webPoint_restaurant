@php
    $title = 'Login';
@endphp
@extends('layouts.app')
@section('content')
    <style>
        body {
            background-color: rgb(66, 68, 117) !important;
            overflow-y:hidden
        }
    </style>
<div class="container-login">
    <section class="welcome-page open">
        <img src="{{asset('global/image/logo.png')}}" alt="Logo" >
        <ul>
            <li class="menu-item" data-value="pos">
                <span>pos</span>
            </li>
            <li class="menu-item" data-value="stock">
                <span>Cost & Stock</span>
            </li>
            <li class="menu-item" data-value="hr">
                <span>HR</span>
            </li>
            <li class="menu-item" data-value="accounting">
                <span>Accounting</span>
            </li>
            <li class="menu-item"  data-value="dashboard">
                <span>Dashboard</span>
            </li>
        </ul>

    </section>

    <section class='login close'>
        <div class='pass-div'>
            <div class="back-btn text-white">
                <i class="fas fa-long-arrow-alt-left"></i>
                <span>Back</span>
            </div>
            <h2 class="login-section text-white"></h2>

            <form method="POST" action="{{ route('check_admin') }}">
                <input type="hidden" id="login_method" name="type"/>
                @csrf
                <select id="email" class="@error('email') is-invalid @enderror form-control email-select"  name="email" placeholder="UserName" required autocomplete="off">
                    @foreach(\App\Models\User::select(['email'])->get() as $user)
                    <option value="{{$user->email}}">{{$user->email}}</option>
                    @endforeach
                </select>
                @error('email')
                <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                @enderror
                <input id="password" type="password" class="d-none @error('password') is-invalid @enderror" name="password" required autocomplete="off">

                <p class='star-pass'></p>

                <button type="submit" class="btn btn-block login-btn">
                    {{ __('Login') }}
                </button>
            </form>

        </div>
        <div class='key-numbers'>
            <span class='number' data-number='1'>1</span>
            <span class='number' data-number='2'>2</span>
            <span class='number' data-number='3'>3</span>
            <span class='number' data-number='4'>4</span>
            <span class='number' data-number='5'>5</span>
            <span class='number' data-number='6'>6</span>
            <span class='number' data-number='7'>7</span>
            <span class='number' data-number='8'>8</span>
            <span class='number' data-number='9'>9</span>
            <span class='number' data-number='0'>0</span>
            <span class='remove' data-number='c'><i class="fas fa-backspace"></i></span>
        </div>
    </section>
</div>
@endsection
