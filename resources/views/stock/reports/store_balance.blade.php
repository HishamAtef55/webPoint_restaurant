@php $title='رصيد المخزن';@endphp
@extends('layouts.app')
@section('content')
    <section class="material_manufacturing">
        <div class="container">
            <div class="bg-light p-4 mb-2 rounded shadow">
                @CSRF
                <div class="row align-items-end">
                    <div class="col">
                        <div class="custom-form">
                            <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                            <label for="date">التاريخ</label>
                        </div>
                    </div>
                    <div class="col">
                        <label for="stores"  class="select-label">المخزن</label>
                        <select class="form-select" id="stores">
                            <option selected disabled>اختر المخزن</option>
                            <option value="1">one</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="main_group" class="select-label">المجموعه الرئيسية</label>
                        <select class="form-select" id="main_group">
                            <option selected disabled>اختر المجموعه الرئيسية</option>
                            <option value="1">الطعام</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="sub_group" class="select-label">المجموعه الفرعية</label>
                        <select class="form-select" id="sub_group">
                        </select>
                    </div>
                    <div class="col">
                        <label for="unit" class="select-label">الوحدة</label>
                        <select class="form-select" id="unit">
                            <option  disabled>اختر الوحدة</option>
                            <option value="large" selected>large</option>
                            <option value="small">small</option>
                        </select>
                    </div>
                </div>
                <hr />
                <div class="d-flex gap-2 mb-1">
                    <button style="width: 150px"class='btn btn-warning fs-6' id="limit_min_btn">أرصدة حد الطلب</button>
                    <button style="width: 150px"class='btn btn-primary fs-6' id="limit_max_btn">أرصدة الحد الأقصي</button>
                    <button style="width: 150px"class='btn btn-secondary fs-6' id="store_balance_btn">رصيد المخزن</button>
                    <button style="width: 150px"class='btn btn-success fs-6' id="inventory_btn">الجرد</button>
                </div>
                <hr />
                <div id="report_content">

                </div>
            </div>
        </div>
    </section>
    @include('includes.reports_ajax.store_balance')
@stop
