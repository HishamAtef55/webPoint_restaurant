@php $title='المجموعات';@endphp
@extends('layouts.stock.app')
@section('content')
<section >
    <h2 class="page-title">{{$title}}</h2>
        <div class="container">
            @CSRF
            <div class='row justify-content-center'>
                <div class="col-md-4">
                    <div class="bg-light p-2 rounded shadow">
                        <div class="custom-form mt-3">
                            <input type="text" name="group_id" id="group_id" value="{{$new_id}}" disabled>
                            <label for="group_id">رقم المجموعة </label>
                        </div>
                        <div class="custom-form mt-3 position-relative">
                            <input type="text" name="group_name" id="group_name">
                            <label for="group_name" >اسم المجموعة </label>
                            <ul class="search-result"></ul>
                        </div>
                    </div>
                    <div class="d-grid gap-2  mt-3">
                        <button class='btn btn-success' id="save_group">Save</button>
                        <button class='btn btn-primary d-none' id="update_group">Update</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="table-responsive rounded" style="min-height: 420px">
                        <table class="table table-light text-center">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">المجموعة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($groups) > 0)
                                @foreach($groups as $group)
                                    <tr>
                                        <th scope="row">{{$group->id}}</th>
                                        <td>{{$group->name}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2">لا يوجد مجموعات</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('includes.stock.Stock_Ajax.mainGroup')
@endsection
