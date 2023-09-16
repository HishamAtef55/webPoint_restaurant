@php
$title = 'User';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section class='accordions-sec'>
    <div class="container">
        <h2 class="section-title">Users Management</h2>
        <a class="btn btn-success mb-3" href="{{ route('View.update.user') }}"> Create New User</a>

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
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $user)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                        @if(!empty($user->getRoleNames()))
                            @foreach($user->getRoleNames() as $v)
                            <label class="badge badge-success">{{ $v }}</label>
                            @endforeach
                        @endif
                        </td>
                        <td>
                        <!-- <a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Show</a> -->
                        <a class="text-dark" href="{{ route('users.edit',$user->id) }}">
                            <i class="far fa-edit fa-lg"></i>
                        </a>
                            <!-- {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                            {!! Form::close() !!} -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {!! $data->render() !!}
    </div>
</section>
@endsection
