@php $title='صرف مخازن';@endphp
@extends('layouts.stock.app')
@section('content')
<section class="purchases">
    <h2 class="page-title">{{$title}}</h2>
    <div class="container">
        <div class="bg-light p-4 mb-2 rounded shadow">
        @CSRF
            <div class="row">
                <div class="col-md-2 stores">
                    <label for="stores"  class="select-label">المخزن</label>
                    <select class="form-select" id="stores">
                        <option selected disabled>اختر المخزن</option>
                        @foreach($stores as $store)
                            <option value="{{$store->id}}">{{$store->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 branch-sec">
                    <label for="branch"  class="select-label">الفرع</label>
                    <select class="form-select" id="branch">
                        <option selected disabled>اختر الفرع</option>
                        @foreach($branches as $branch)
                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 branch-sec">
                    <label for="sections"  class="select-label">القسم</label>
                    <select class="form-select" id="sections">
                        <option selected disabled>اختر القسم</option>
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
                    <button class="btn btn-primary" id="showExchangeReport">عرض الصرف</button>
                </div>
            </div>
            <hr />
            <div id="report_content"></div>
        </div>



        <!-- <tfoot class="table-dark">
                            <th colspan="5"> الاجمالي </th>
                            <th class="sumPrice">  </th>
                            <th class="sumQty">  </th>
                            <th class="sumTotal">  </th>
                    </tfoot> -->

    </div>
</section>
@include('includes.stock.reports_ajax.exchange')
@endsection
