@php $title='المجموعات';@endphp
@extends('layouts.app')
@section('content')
    <section class='pt-2'>
        <div class="container">
            @CSRF
            <div class='row'>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="group_id" class="form-label">رقم المجموعة </label>
                        <input type="text" class="form-control" name="group_id" id="group_id" value="{{$new_id}}" disabled>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="group_name" class="form-label">اسم المجموعة </label>
                        <input type="text" class="form-control" name="group_name" id="group_name">
                        <ul class="search-result"></ul>
                    </div>
                    <div class="d-grid gap-2 col-md-6 mx-auto mt-4">
                        <button class='btn btn-success' id="save_group">Save</button>
                        <button class='btn btn-primary d-none' id="update_group">Update</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-light shadow text-center">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">المجموعة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($groups as $group)
                                <tr>
                                    <th scope="row">{{$group->id}}</th>
                                    <td>{{$group->name}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('includes.stock.Stock_Ajax.mainGroup')
@endsection
