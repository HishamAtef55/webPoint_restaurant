@php
$title = 'Permission';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section class='accordions-sec'>
    <div class="container">
        <h2 class="section-title">Role Management</h2>
        @can('role-create')
        <a class="btn btn-success mb-3" href="{{ route('roles.create') }}"> Create New Role</a>
        @endcan

        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($roles as $key => $role)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            <!-- <a class="btn btn-info" href="{{ route('roles.show',$role->id) }}">Show</a> -->
                            @can('role-edit')
                                <a class="text-dark btn px-1" href="{{ route('roles.edit',$role->id) }}">
                                    <i class="far fa-edit fa-lg"></i>
                                </a>
                            @endcan

                            @can('role-delete')
                                {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                <button type="submit" class="btn px-1">
                                    <i class="fas fa-trash fa-lg"></i>
                                </button>
                                <!-- {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!} -->
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {!! $roles->render() !!}
    </div>
</section>
@endsection
