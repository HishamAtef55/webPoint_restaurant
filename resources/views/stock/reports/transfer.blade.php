@php $title='تحويل مخازن';@endphp
@extends('layouts.stock.app')
@section('content')
<section class="purchases">
    <div class="container">
        <div class="bg-light p-4 mb-2 rounded shadow">
            @CSRF
            <div class="row align-items-end" style="margin-top: -1rem;">
                <div class="col-md-2">
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
                    <label for="fromStore"  class="select-label">من مخزن</label>
                    <select class="form-select" id="fromStore">
                        <option selected disabled>اختر المخزن</option>
                        <option value="all">ِAll</option>
                        @foreach($stores as $store)
                            <option value="{{$store->id}}">{{$store->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 stores">
                    <label for="toStore"  class="select-label">الي مخزن</label>
                    <select class="form-select" id="toStore">
                        <option selected disabled>اختر المخزن</option>
                        <option value="all">All</option>
                    @foreach($stores as $store)
                            <option value="{{$store->id}}">{{$store->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 branch-sec d-none">
                    <label for="branch"  class="select-label">الفرع</label>
                    <select class="form-select" id="branch">
                        <option selected disabled>اختر الفرع</option>
                        @foreach($branches as $branch)
                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 branch-sec d-none">
                    <label for="fromSection"  class="select-label">من قسم</label>
                    <select class="form-select" id="fromSection">
                    </select>
                </div>
                <div class="col-md-3 branch-sec d-none">
                    <label for="toSection"  class="select-label">الي قسم</label>
                    <select class="form-select" id="toSection">
                    </select>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-3">
                    <div class="custom-form">
                        <input type="date"  name="date_from" id="date_from" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                        <label for="date" >التاريخ من</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="custom-form">
                        <input type="date"  name="date_to" id="date_to" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                        <label for="date" >التاريخ إلى</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary" id="showTransferReport">عرض التحويل</button>
                </div>
            </div>
            <hr />
            <div id="report_content"></div>
        </div>
    </div>
</section>
@include('includes.stock.reports_ajax.transfer')
@endsection
