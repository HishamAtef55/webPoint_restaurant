@php $title='الاقسام';@endphp
@extends('layouts.stock.app')
@section('content')
<section>
    <h2 class="page-title">{{$title}}</h2>
    <div class="container">
        @CSRF
        <div class='row justify-content-center'>
            <div class="col-md-4">
                <div class="bg-light p-2 rounded shadow">
                    <div class="custom-form mt-3">
                        <input type="text" name="section_id" id="section_id" value="{{$new_section}}" disabled>
                        <label for="section_id" >رقم القسم</label>
                    </div>
                    <div>
                        <label for="store" class="select-label">اسم المخزن</label>
                        <select class="form-select unit" id="store">
                            <option selected disabled>اختر المخزن</option>
                            @foreach($stores as $store)
                                <option value="{{$store->id}}">{{$store->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="branch" class="select-label">اسم الفرع</label>
                        <select class="form-select unit" id="branch">
                            <option selected disabled>اختر الفرع</option>
                            @foreach($branchs as $branch)
                                <option value="{{$branch->id}}">{{$branch->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="custom-form mt-3 position-relative">
                        <input type="text"  name="section_name" id="section_name">
                        <label for="section_name" >اسم القسم</label>
                        <ul class="search-result"></ul>
                    </div>
                </div>
                <div class="bg-light p-2 rounded shadow mt-2">
                    <h3>المجموعات</h3>
                    <div class="groups">
                    </div>
                </div>

                <div class="d-grid gap-2  mt-3">
                    <button class='btn btn-success' id="save_section">Save</button>
                    <button class='btn btn-primary d-none' id="update_section">Update</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-responsive rounded"  style="min-height: 420px">
                    <table class="table table-light text-center">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">الفرع</th>
                            <th scope="col">الاسم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($sections) > 0)
                            @foreach($sections as $section)
                                <tr>
                                    <th scope="row">{{$section->id}}</th>
                                    <td>{{$section->sectionsBranch->name}}</td>
                                    <td>{{$section->name}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">لا يوجد أقسام</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@include('includes.stock.Stock_Ajax.sections')
@endsection
