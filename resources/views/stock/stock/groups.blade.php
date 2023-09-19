@php $title='المجموعات الفرعية';@endphp
@extends('layouts.stock.app')
@section('content')
<section>
    <h2 class="page-title">{{$title}}</h2>
    <div class="container">
        @CSRF
        <div class='row justify-content-center'>
            <div class="col-md-5">
                <div class="bg-light p-2 rounded shadow">
                    <div class="custom-form mt-3">
                        <input type="text" name="group_id" id="group_id" value="{{$new_group}}" disabled>
                        <label for="group_id">رقم المجموعة الفرعية</label>
                    </div>
                    <div>
                        <label for="main_group"  class="select-label">المجموعه الرئيسية</label>
                        <select class="form-select" id="main_group">
                            <option selected disabled>اختر المجموعه الرئيسية</option>
                            @foreach($mainGroup as $group)
                                <option value="{{$group->id}}">{{$group->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="custom-form mt-3 position-relative">
                        <input type="text" name="group_name" id="group_name">
                        <label for="group_name">اسم المجموعة الفرعية</label>
                        <ul class="search-result"></ul>
                    </div>
                    <div class="custom-form mt-3">
                        <input type="text" name="group_from" id="group_from">
                        <label for="group_from">بداية الترقيم</label>
                    </div>
                    <div class="custom-form mt-3">
                        <input type="text" name="group_to" id="group_to">
                        <label for="group_to">نهاية الترقيم</label>
                    </div>
                </div>

                <div class="d-grid gap-2  mt-3">
                    <button class='btn btn-success' id="save_group">Save</button>
                    <button class='btn btn-primary d-none' id="update_group">Update</button>
                </div>
            </div>
            <div class="col-md-7">
                <div class="table-responsive rounded"  style="min-height: 420px">
                    <table class="table table-light text-center">
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
                        @if(count($groups) > 0)
                            @foreach($groups as $group)
                                <tr>
                                    <th scope="row">{{$group->id}}</th>
                                    <td>{{$group->maingroup->name}}</td>
                                    <td>{{$group->name}}</td>
                                    <td>{{$group->start_serial}}</td>
                                    <td>{{$group->end_serial}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">لا يوجد مجموعات</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
    @include('includes.stock.Stock_Ajax.groups')
@endsection
