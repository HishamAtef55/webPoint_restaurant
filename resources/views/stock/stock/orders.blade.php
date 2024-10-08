@php $title='الطلبية';@endphp
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
                        <input type="file" class="form-control"  name="permission_file" id="permission_file">
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
                    <div class="col-md-1">
                        <div class="form-check">
                            <input class="form-check-input purchases-method" type="radio" value="section"
                                id="sections_method" name="purchases_method">
                            <label class="form-check-label" for="sections_method">
                                اقسام
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input purchases-method" type="radio" value="store"
                                id="stores_method" name="purchases_method" checked>
                            <label class="form-check-label" for="stores_method">
                                مخازن
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 stores">
                        <label for="stores"  class="select-label">المخزن</label>
                        <select class="form-select" id="stores">
                            <option selected disabled>اختر المخزن</option>
                            @foreach($stores as $store)
                                <option value="{{$store->id}}">{{$store->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 branch-sec d-none">
                        <label for="branch"  class="select-label">الفرع</label>
                        <select class="form-select" id="branch">
                            <option selected disabled>اختر الفرع</option>
                            @foreach($branches as $branch)
                                <option value="{{$branch->id}}">{{$branch->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 branch-sec d-none">
                        <label for="sections"  class="select-label">القسم</label>
                        <select class="form-select" id="sections">
                            <option selected disabled>اختر القسم</option>
                        </select>
                    </div>
                </div>
                <hr />
                <div class="row align-items-end" style="margin-top: -1rem;">
                    <div class="col-md-2">
                        <label for="items"  class="select-label"> الصنف</label>
                        <select class="form-select" id="items">
                            <option selected disabled>اختر الصنف</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="unit" class="select-label" >الوحدة</label>
                        <select class="form-select" id="unit">
                            <option selected disabled>اختر الوحدة</option>
                        </select>
                    </div>
                    <div class="col">
                        <div class="custom-form">
                            <input type="number"  name="price_unit" id="price_unit">
                            <label for="price_unit" >السعر</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="custom-form">
                            <input type="number"  name="quantity" id="quantity">
                            <label for="quantity" >الكمية</label>
                            <span class='invalid-feedback'></span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="custom-form">
                            <input type="number"  name="total_unit" id="total_unit" disabled>
                            <label for="total_unit" >الاجمالى</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="custom-form">
                            <input type="text"  name="current_balance" id="current_balance" disabled>
                            <label for="current_balance"> الرصيد الحالى</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="custom-form">
                            <input type="number"  name="max" id="max" disabled>
                            <label for="current_balance"> Max </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive materials-responsive rounded">
                <table class="table table-light table-striped text-center table-purchases">
                    <thead>
                        <tr>
                            <th> كود الصنف </th>
                            <th> اسم الصنف </th>
                            <th> الوحدة </th>
                            <th> السعر </th>
                            <th> الكمية </th>
                            <th> الاجمالى </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="not-found">
                            <td colspan="7">لا يوجد بيانات</td>
                        </tr>
                    </tbody>
                    <tfoot class="table-dark">
                            <th colspan="5"> الاجمالي </th>
                            <th class="sumTotal">0.00</th>
                            <th></th>
                    </tfoot>
                </table>
            </div>
            <div class="d-grid gap-2 col-md-6 mx-auto mt-4">
                <button class='btn btn-success fs-6' id="save_purchases">Save</button>
                <div class="d-flex gap-2">
                    <button class='btn btn-danger fs-6 d-none col' id="delete_purchases">Delete</button>
                    <button class='btn btn-primary fs-6 d-none col' id="update_purchases">Update</button>
                </div>
            </div>
        </div>
    </section>
@include('includes.stock.Stock_Ajax.orders')
@endsection
