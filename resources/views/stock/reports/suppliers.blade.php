@php $title=' تقرير الموردين ';@endphp
@extends('layouts.stock.app')
@section('content')
<section>
    <h2 class="page-title">{{$title}}</h2>
    <div class="container">
        <div class="bg-light p-4 mb-2 rounded shadow">
            @CSRF
            <div class="row align-items-end" style="margin-top: -1rem;">
                <div class="col-md-3">
                    <label for="supplier" class="select-label">الموردين</label>
                    <select class="form-select supplier" id="supplier">
                        <option selected disabled>اختر المورد</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="custom-form">
                        <input type="date" name="date_from" id="date_from" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                        <label for="date_from">التاريخ من</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="custom-form">
                        <input type="date" name="date_to" id="date_to" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                        <label for="date_to">التاريخ إلى</label>
                    </div>
                </div>
                <div class="col">
                    <button class="btn btn-primary showTransferReport">عرض</button>
                </div>
            </div>
            <hr />
            <div id="report_content"></div>
        </div>
    </div>
</section>
@include('includes.stock.reports_ajax.suppliers')
@endsection