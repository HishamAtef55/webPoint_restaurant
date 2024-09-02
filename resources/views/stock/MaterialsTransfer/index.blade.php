@php $title='إذن تحويل';@endphp
@extends('layouts.stock.app')
@section('content')
    <section>
        <h2 class="page-title">{{ $title }}</h2>
        <div class="container">
            <div class="row">
                <div class="col-md-12 d-flex align-items-center mb-2">
                    <div class="col-md-1">
                        <button type="button" class="btn btn-success" onClick="window.location.reload()">
                            Refresh
                        </button>
                    </div>
                    <div class="col-md-3">

                        <select class="form-select" name="_transfer_id" id="_transfer_id">
                            <option selected disabled>اختر رقم الإذن</option>
                            @forelse ($transfers as $transfer)
                                <option value="{{ $transfer->id }}">{{ $transfer->id }}</option>
                            @empty
                                <option value="">لاتوجد تحويلات متاحة</option>
                            @endforelse
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-light p-4 mb-2 rounded shadow invoice">
                <div class="row">
                    <div class="col-md-2">
                        <div class="custom-form invalid">
                            <input type="number" value="{{ $lastTransferNr }}" name="transfer_id" id="transfer_id">
                            <label for="transfer_id">رقم الاذن</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="custom-form">
                            <input type="number" name="serial_number" id="serial_number">
                            <label for="serial_number">رقم المسلسل</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="custom-form invalid">
                            <input type="date" name="transfer_date" id="transfer_date" value="<?php echo date('Y-m-d'); ?>">
                            <label for="transfer_date">التاريخ</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <input type="file" class="form-control" name="image" id="image">
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
                    <div class="col-md-1">
                        <div class="form-check">
                            <input class="form-check-input purchases-method" type="radio" value="sections"
                                id="sections_method" name="purchases_method">
                            <label class="form-check-label" for="sections_method">
                                اقسام
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input purchases-method" type="radio" value="stores"
                                id="stores_method" name="purchases_method" checked>
                            <label class="form-check-label" for="stores_method">
                                مخازن
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 stores">
                        <label for="from_store_id" class="select-label">من مخزن</label>
                        <select class="form-select" id="from_store_id" name="from_store_id">
                            <option value="" selected disabled>اختر المخزن</option>
                            @forelse ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @empty
                                <option value="">لايوجد مخازن</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-3 stores">
                        <label for="to_store_id" class="select-label">الى مخزن</label>
                        <select class="form-select" id="to_store_id" name="to_store_id">
                            <option value="" selected disabled>اختر المخزن</option>
                            @forelse ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @empty
                                <option value="">لايوجد مخازن</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-2 branch-sec d-none">
                        <label for="from_branch_id" class="select-label">من فرع</label>
                        <select class="form-select" id="from_branch_id" name="from_branch_id">
                            <option value="" selected disabled>اختر الفرع</option>
                            @forelse ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @empty
                                <option value="">لايوجد فروع</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-2 branch-sec d-none">
                        <label for="from_section_id" class="select-label">من قسم</label>
                        <select class="form-select" name="from_section_id" id="from_section_id">
                            <option selected disabled>اختر القسم</option>

                        </select>
                    </div>

                    <div class="col-md-2 branch-sec d-none">
                        <label for="to_branch_id" class="select-label">الى فرع</label>
                        <select class="form-select" id="to_branch_id" name="to_branch_id">
                            <option value="" selected disabled>اختر الفرع</option>
                            @forelse ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @empty
                                <option value="">لايوجد فروع</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-2 branch-sec d-none">
                        <label for="to_section_id" class="select-label">الى قسم</label>
                        <select class="form-select" name="to_section_id" id="to_section_id">
                            <option selected disabled>اختر القسم</option>

                        </select>
                    </div>

                </div>
                <hr />
                <div class="row align-items-end" style="margin-top: -1rem;" id="transfer_materials">
                    <div class="col-md-3">
                        <label for="material_id" class="select-label"> الخامات</label>
                        <select class="form-select" name="material_id" id="material_id">
                            <option value="" selected disabled>اختر الخامة</option>
                        </select>
                    </div>
                    <div class="col">
                        <div class="custom-form">
                            <input type="text" disabled name="unit" id="unit">
                            <label for="unit">الوحدة</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="custom-form">
                            <input type="number" name="price" id="price">
                            <label for="price">السعر</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="custom-form">
                            <input type="number" name="quantity" id="quantity">
                            <label for="quantity">الكمية</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="custom-form">
                            <input type="number" name="total_price" id="total_price">
                            <label for="total_price">الاجمالى</label>
                        </div>
                    </div>

                    <div class="col">
                        <div class="custom-form">
                            <input type="text" name="current_balance" id="current_balance" disabled>
                            <label for="current_balance"> الرصيد الحالى</label>
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
                            <th>تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="not-found">
                            <td colspan="7">لا يوجد بيانات</td>
                        </tr>
                    </tbody>
                    <tfoot class="table-dark">
                        <th colspan="5"> الاجمالي </th>
                        <th class="sumFinal"> </th>
                        <th></th>
                    </tfoot>
                </table>
            </div>
            <div class="d-grid gap-2 col-md-6 mx-auto mt-4 mb-2">
                <button class='btn btn-success fs-6' id="save_material_transfer">حفظ</button>
                <button class='btn btn-primary fs-6 d-none' id="update_material_transfer">تعديل</button>
            </div>
        </div>
    </section>
    @include('includes.stock.Stock_Ajax.materials_transfer')
@endsection
