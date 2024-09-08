@php $title='المشتريات';@endphp
@extends('layouts.stock.app')
@section('content')
    <section>
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

                        <select class="form-select" name="purchases_id" id="purchases_id">
                            <option selected disabled>اختر رقم الفاتورة</option>
                            @forelse ($invoices as $invoice)
                                <option value="{{ $invoice->id }}">{{ $invoice->id }}</option>
                            @empty
                                <option value="">لاتوجد فواتير متاحة</option>
                            @endforelse
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-light p-4 mb-2 rounded shadow invoice">
                <div class="row">
                    <div class="col-md-2">
                        <div class="custom-form invalid">
                            <input type="number" value="{{ $lastPurchaseslNr }}" name="invoice_id" id="invoice_id">
                            <label for="invoice_id">رقم الاذن</label>
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
                            <input type="date" name="purchases_date" id="purchases_date" value="<?php echo date('Y-m-d'); ?>">
                            <label for="purchases_date">التاريخ</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <input type="file" class="form-control" name="invoice_image" id="invoice_image">
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
                    <div class="col-md-1 method-checkbox">
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
                    <div class="col-md-3">
                        <label for="supplier_id" class="select-label">الموردين</label>
                        <select class="form-select" name="supplier_id" id="supplier_id">
                            <option selected disabled>اختر المورد</option>
                            @forelse ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @empty
                                <option value="">لايوجد موردين</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-3 stores">
                        <label for="store_id" class="select-label">المخزن</label>
                        <select class="form-select" id="store_id" name="store_id">
                            <option value="" selected disabled>اختر المخزن</option>
                            @forelse ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @empty
                                <option value="">لايوجد مخازن</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-2 branch-sec d-none">
                        <label for="branch_id" class="select-label">الفرع</label>
                        <select class="form-select" id="branch_id" name="branch_id">
                            <option value="" selected disabled>اختر الفرع</option>
                            @forelse ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @empty
                                <option value="">لايوجد فروع</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-2 branch-sec d-none">
                        <label for="section_id" class="select-label">القسم</label>
                        <select class="form-select" name="section_id" id="section_id">
                            <option selected disabled>اختر القسم</option>

                        </select>
                    </div>
                    <div class="col">
                        <div class="custom-form">
                            <input type="number" name="tax" id="tax">
                            <label for="tax"> (%) الضريبة</label>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="installment" id="installment_method"
                                name="pay_method">
                            <label class="form-check-label" for="installment_method">
                                اجل
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="cash" id="cash_method"
                                name="pay_method" checked>
                            <label class="form-check-label" for="cash_method">
                                نقدى
                            </label>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row align-items-end" style="margin-top: -1rem;" id="material_purchases">
                    <div class="col-md-2">
                        <label for="material_id" class="select-label"> الخامات</label>
                        <select class="form-select" name="material_id" id="material_id">
                            <option value="" selected disabled>اختر الخامة</option>
                            @forelse ($materials as $material)
                                <option value="{{ $material->id }}" data-last-price="{{ $material->details?->price }}"
                                    data-unit="{{ $material->unit['sub_unit']['name_ar'] }}"
                                    data-unit-name-en="{{ $material->unit['sub_unit']['name_en'] }}"
                                    data-unit-value="{{ $material->unit['sub_unit']['value'] }}">
                                    {{ $material->name }}</option>
                            @empty
                                <option value="">توجد خامات</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="custom-form invalid">
                            <input type="date" name="expire_date" id="expire_date" value="<?php echo date('Y-m-d'); ?>">
                            <label for="expire_date">تاريخ الصلاحية</label>
                        </div>
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
                            <input type="number" name="discount" id="discount">
                            <label for="discount">الخصم</label>
                        </div>
                    </div>

                    <div class="col">
                        <div class="custom-form">
                            <input type="text" name="last_price" id="last_price" disabled>
                            <label for="last_price">اخر سعر</label>
                        </div>
                    </div>

                    <div class="col">
                        <div class="custom-form">
                            {{-- <button class="btn btn-primary" id="arrow-down">

                                <i class="fa-solid fa-arrow-down"></i>
                            </button> --}}
                            <input type="text" name="current_balance" id="current_balance" disabled>
                            <label for="current_balance">الرصيد</label>
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
                            <th> تاريخ الصلاحية </th>
                            <th> الوحدة </th>
                            <th> السعر </th>
                            <th> الكمية </th>
                            <th> التكلفة </th>
                            <th> الخصم </th>
                            <th> الاجمالى </th>
                            <th>تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="not-found">
                            <td colspan="10">لا يوجد بيانات</td>
                        </tr>
                    </tbody>
                    <tfoot class="table-dark">

                        <tr>
                            <th colspan="6"> الاجمالي </th>
                            <th class="sumTotal">0</th>

                            <th class="sumDiscount">0</th>
                            <th class="sumFinal">0</th>
                            <th></th>

                        </tr>
                        <tr>

                            <th colspan="6"> الضريبة </th>
                            <th></th>
                            <th></th>
                            <th class="sumTax">0</th>
                            <th></th>
                        </tr>

                        <tr>

                            <th colspan="6"> صافى الاجمالى </th>
                            <th></th>
                            <th></th>
                            <th class="netTotalPrice">0</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="d-grid gap-2 col-md-6 mx-auto mt-4 mb-2">
                <button class='btn btn-success fs-6' id="save_purchases">حفظ</button>
                <button class='btn btn-primary fs-6 d-none' id="update_purchases">تعديل</button>
            </div>
        </div>
    </section>
    @include('includes.stock.Stock_Ajax.purchases')
@endsection
