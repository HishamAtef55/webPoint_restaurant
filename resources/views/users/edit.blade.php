@php
$title = 'Edit User';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section class='accordions-sec'>
    <div class="container">
        <h2 class="section-title">Edit User</h2>
        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-12">
                <a class="btn btn-warning mb-2" href="{{ route('users.index') }}"> Back</a>

                @if (count($errors) > 0)
                <div class="alert alert-danger">

                    <strong>Whoops!</strong> There were some problems with your input.<br><br>

                    <ul>

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>
                @endif

                {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
                <div class="mb-3">
                    <strong>Name:</strong>
                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control disabled')) !!}
                </div>
                <!-- <div class="mb-3">
                    <strong>Email:</strong>
                    {!! Form::text('text', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                </div>
                <div class="mb-3">
                    <strong>Password:</strong>
                    {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                </div>
                <div class="mb-3">
                    <strong>Confirm Password:</strong>
                    {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                </div> -->
                <div class="mb-3">
                    <strong>Role:</strong>
                    {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control','multiple')) !!}
                </div>
                <div class='col-md-6 offset-md-3'>
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
@endsection
