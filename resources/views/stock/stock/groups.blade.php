@php $title='المجموعات الفرعية';@endphp
@extends('layouts.stock.app')
@section('content')
<section class='pt-2'>
    <div class="container">
        @CSRF
        <div class='row'>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="group_id" class="form-label">رقم المجموعة الفرعية</label>
                    <input type="text" class="form-control" name="group_id" id="group_id" value="{{$new_group}}" disabled>
                </div>
                <div class="mb-3">
                    <label for="store" class="form-label">المجموعه الرئيسية</label>
                    <select class="form-select unit" id="main_group">
                        <option selected disabled>اختر المجموعه الرئيسية</option>
                        @foreach($mainGroup as $group)
                            <option value="{{$group->id}}">{{$group->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 position-relative">
                    <label for="group_name" class="form-label">اسم المجموعة الفرعية</label>
                    <input type="text" class="form-control" name="group_name" id="group_name">
                    <ul class="search-result"></ul>
                </div>
                <div class="mb-3">
                    <label for="group_from" class="form-label">بداية الترقيم</label>
                    <input type="text" class="form-control" name="group_from" id="group_from">
                </div>
                <div class="mb-3">
                    <label for="group_to" class="form-label">نهاية الترقيم</label>
                    <input type="text" class="form-control" name="group_to" id="group_to">
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
                            <th scope="col">المجموعة الرئيسية</th>
                            <th scope="col">المجموعة الفرعية </th>
                            <th scope="col">بداية الترقيم</th>
                            <th scope="col">نهاية الترقيم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($groups as $group)
                            <tr>
                                <th scope="row">{{$group->id}}</th>
                                <td>{{$group->maingroup->name}}</td>
                                <td>{{$group->name}}</td>
                                <td>{{$group->start_serial}}</td>
                                <td>{{$group->end_serial}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
    @include('includes.stock.Stock_Ajax.groups')
@endsection
