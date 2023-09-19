@php $title='إذن هالك صنف';@endphp
@extends('layouts.stock.app')
@section('content')
<section class="purchases">
    <h2 class="page-title">{{$title}}</h2>
    <div id="permissionId" value="{{$serial}}"></div>
    <div class="container">
        <div class="bg-light p-4 mb-2 rounded shadow">
            @CSRF
            <div class="row">
                <div class="col-md-2">
                    <div class="custom-form">
                        <input type="number" value="{{$serial}}" name="permission" id="permission">
                        <label for="permission">رقم الاذن</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="custom-form">
                        <input type="date"  name="date" id="date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d',strtotime("-1 days")); ?>">
                        <label for="date" >التاريخ</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="custom-form">
                        <textarea  name="notes" id="notes"></textarea>
                        <label for="notes" >الملاحظات</label>
                    </div>
                </div>
            </div>
            <hr />
            <div class="row align-items-end" style="margin-top: -1rem;">
                <div class="col-md-2 branch-sec">
                    <label for="branch"  class="select-label">الفرع</label>
                    <select class="form-select" id="branch">
                        <option selected disabled>اختر الفرع</option>
                        @foreach($branches as $branch)
                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="items"  class="select-label"> الصنف</label>
                    <select class="form-select" id="items">
                        <option selected disabled>اختر الصنف</option>
                    </select>
                </div>
                <div class="col">
                    <div class="custom-form">
                        <input type="number" name="quantity" id="quantity">
                        <label for="quantity" >الكمية</label>
                    </div>
                </div>
                <div class="col-md-2 branch-sec">
                    <label for="section"  class="select-label">من قسم</label>
                    <select class="form-select" id="section">
                        <option selected disabled>اختر القسم</option>
                    </select>
                </div>
                <button class="btn btn-success col" id="saveHalk">حفظ</button>
            </div>
        </div>
        <div class="table-responsive materials-responsive rounded">
            <table class="table table-light table-striped text-center table-purchases">
                <thead>
                    <tr>
                        <th> # </th>
                        <th>الفرع</th>
                        <th> القسم </th>
                        <th> اسم الصنف </th>
                        <th> الكمية المهلكة </th>
                        <th> التاريخ </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tableView">
                    @if(count($halkItems) > 0)
                        @foreach($halkItems as $halk)
                            <tr id="{{$halk->id}}">
                                <td>{{$halk->id}}</td>
                                <td>{{$halk->getbranch->name}}</td>
                                <td>{{$halk->getsection->name}}</td>
                                <td>{{$halk->item}}</td>
                                <td>{{$halk->qty}}</td>
                                <td>{{$halk->date}}</td>
                                <td>
                                    <div class="del-edit">
                                        <button class="btn btn-danger delete" halkId="{{$halk->id}}"><i class="fa-regular fa-trash-can"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="not-found">
                            <td colspan="7">لا يوجد هالك</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</section>
@include('includes.stock.Stock_Ajax.halk_item')
@endsection
