@php $title='الاقسام';@endphp
@extends('layouts.stock.app')
@section('content')
<section class='store pt-2'>
    <h2 class="page-title">{{$title}}</h2>
    <div class="container">
        @CSRF
        <div class='row'>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="section_id" class="form-label">رقم القسم</label>
                    <input type="text" class="form-control" name="section_id" id="section_id" value="{{$new_section}}"
                        disabled>
                </div>
                <div class="mb-3">
                    <label for="store" class="form-label">اسم المخزن</label>
                    <select class="form-select unit" id="store">
                        <option selected disabled>اختر المخزن</option>
                        @foreach($stores as $store)
                            <option value="{{$store->id}}">{{$store->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="branch" class="form-label">اسم الفرع</label>
                    <select class="form-select unit" id="branch">
                        <option selected disabled>اختر الفرع</option>
                        @foreach($branchs as $branch)
                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 position-relative">
                    <label for="section_name" class="form-label">اسم القسم</label>
                    <input type="text" class="form-control" name="section_name" id="section_name">
                    <ul class="search-result"></ul>
                </div>

                <h3>المجموعات</h3>
                <div class="groups">
                </div>
                <div class="d-grid gap-2 col-md-6 mx-auto mt-4">
                    <button class='btn btn-success' id="save_section">Save</button>
                    <button class='btn btn-primary d-none' id="update_section">Update</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-responsive">
                    <table class="table table-light shadow text-center">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">الفرع</th>
                            <th scope="col">الاسم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sections as $section)
                            <tr>
                                <th scope="row">{{$section->id}}</th>
                                <td>{{$section->sectionsBranch->name}}</td>
                                <td>{{$section->name}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@include('includes.stock.Stock_Ajax.sections')
@endsection
