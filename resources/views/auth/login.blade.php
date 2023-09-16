@php
    $title = 'Login';
@endphp
@extends('layouts.app')
@section('content')
    <style>
        body {
            background-color: rgb(66, 68, 117) !important;
        }
    </style>
<div class="container container-login ibrahim">
    <section class='login'>
        <div class='pass-div'>
            <img src="{{asset('global/image/logo.png')}}" width="160px" style="margin-bottom: 25px"  alt="Logo" >

            <form method="POST" action="{{ route('login') }}">
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
