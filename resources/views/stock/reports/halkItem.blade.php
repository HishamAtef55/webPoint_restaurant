@php $title=' هالك صنف ';@endphp
@extends('layouts.stock.app')
@section('content')
<section class="purchases">
    <h2 class="page-title">{{$title}}</h2>
    <div class="container">
        <div class="bg-light p-4 mb-2 rounded shadow">
            @CSRF
            <div class="row align-items-end" style="margin-top: -1rem;">
                <div class="col-md-2 branch-sec">
                    <label for="branch" class="select-label">الفرع</label>
                    <select class="form-select" id="branch">
                        <option selected disabled>اختر الفرع</option>
                        <option value="1">branxh1</option>

                    </select>
                </div>
                <div class="col-md-2 branch-sec">
                    <label for="section" class="select-label">القسم</label>
                    <select class="form-select" id="section">
                        <option selected disabled>اختر القسم</option>
                    </select>
                </div>
            </div>
            <hr />
            <div class="row">
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
                    <button class="btn btn-primary showTransferReport" data-request="details">عرض الهالك</button>
                    <button class="btn btn-warning showTransferReport" data-request="total">عرض اجمالي الهالك</button>
                </div>
            </div>
            <hr />
            <div id="report_content"></div>
        </div>
    </div>
</section>
@include('includes.stock.reports_ajax.halkItem')
@endsection