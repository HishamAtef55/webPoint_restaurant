@php $title='إذن هالك صنف';@endphp
@extends('layouts.stock.app')
@section('content')
    <section class="purchases">
        <h2 class="page-title">{{ $title }}</h2>
        <div class="container">
            <div class="row">
                <div class="col-md-12 d-flex align-items-center mb-2">
                    <div class="col-md-1">
                        <button type="button" class="btn btn-success" onClick="window.location.reload()">
                            جديد
                        </button>
                    </div>
                    <div class="col-md-3">

                        <select class="form-select" name="halk_item_nr" id="halk_item_nr">
                            <option selected disabled>اختر رقم الإذن</option>
                            @forelse ($halksItem as $halk)
                                <option value="{{ $halk->id }}">{{ $halk->id }}</option>
                            @empty
                                <option value="">لايوجد هالك متاح</option>
                            @endforelse
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-light p-4 mb-2 rounded shadow">
                <div class="row">
                    <div class="col-md-2">
                        <div class="custom-form">
                            <input type="number" value="{{ $lastHalkItemNr }}" name="halk_id" id="halk_id">
                            <label for="halk_id">رقم الاذن</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="custom-form">
                            <input type="number" name="serial_nr" id="serial_nr">
                            <label for="serial_nr">رقم المسلسل</label>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="custom-form invalid">
                            <input type="date" name="halk_date" id="halk_date" value="<?php echo date('Y-m-d'); ?>">
                            <label for="halk_date">التاريخ</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="custom-form">
                            <textarea name="notes" id="notes"></textarea>
                            <label for="notes">الملاحظات</label>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row align-items-end" style="margin-top: -1rem;">
                    <div class="col-md-3 branch-sec">
                        <label for="branch_id" class="select-label">من فرع</label>
                        <select class="form-select" id="branch_id" name="branch_id">
                            <option value="" selected disabled>اختر الفرع</option>
                            @forelse ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @empty
                                <option value="">لايوجد فروع</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-3 branch-sec">
                        <label for="section_id" class="select-label">من قسم</label>
                        <select class="form-select" name="section_id" id="section_id">
                            <option selected disabled>اختر القسم</option>

                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="item_id" class="select-label"> الصنف</label>
                        <select class="form-select" id="item_id">
                            <option selected disabled>اختر الصنف</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="custom-form">
                            <input type="number" name="quantity" id="quantity">
                            <label for="quantity">الكمية</label>
                        </div>
                    </div>

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
                            <th>تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="not-found">
                            <td colspan="6">لا يوجد بيانات</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-grid gap-2 col-md-6 mx-auto mt-4 mb-2">
                <button class='btn btn-success fs-6' id="save_material_halk">حفظ</button>
                <button class='btn btn-primary fs-6 d-none' id="update_material_halk">تعديل</button>
            </div>
        </div>
    </section>
    @include('includes.stock.Stock_Ajax.materials_halk_item')
@endsection
