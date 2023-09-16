@php
    $title = 'Welcome';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section class="center" style="background-color: #1b3958;height: calc(100vh);margin-top: -1.5rem;display: flex;align-items: center;justify-content: center">
	<img style="width: 30%; margin: auto;display: block" src="{{asset('global/image/logo.png')}}"/>
</section>
@stop
