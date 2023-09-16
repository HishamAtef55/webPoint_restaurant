@php
$title = 'Create Role';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section class='accordions-sec'>
    <div class="container">
        <h2 class="section-title">Create New Role</h2>
        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-12">
                <a class="btn btn-warning mb-2" href="{{ route('roles.index') }}"> Back</a>

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


                {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
                    <div class="mb-3">
                        <strong>Name:</strong>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>

                    <div class="mb-3">
                        <strong>Permission:</strong>
                        <br/>
                        @foreach($permission as $value)
                            <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                                {{ $value->name }}</label>
                            <br/>
                        @endforeach
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
