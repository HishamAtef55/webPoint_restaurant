@php $title='إذن صرف';@endphp
@extends('layouts.stock.app')
@section('content')
    <section class="exchange">
        <div class="container">
            <div class="row">
                <div class="col-md-12 d-flex align-items-center mb-2">
                    <div class="col-md-1">
                        <button type="button" class="btn btn-success" onClick="window.location.reload()">
                            Refresh
                        </button>
                    </div>
                    <div class="col-md-3">

                        <select class="form-select" name="purchases_id" id="purchases_id">
                            <option selected disabled>اختر رقم الطلبية</option>
                            @forelse ($orders as $order)
                                <option value="{{ $order->id }}">{{ $order->order_nr }}</option>
                            @empty
                                <option value="">لاتوجد طلبيات متاحة</option>
                            @endforelse
                        </select>
                    </div>
                </div>
            </div>
            <div class="bg-light p-4 mb-2 rounded shadow">
                <div class="row">
                    <div class="col-md-2">
                        <div class="custom-form invalid">
                            <input type="number" value="{{ $lastExchangeNr }}" name="invoice_id" id="invoice_id">
                            <label for="invoice_id">رقم الاذن</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="custom-form">
                            <input type="number" name="order_nr" id="order_nr">
                            <label for="order_nr">رقم الطلبية</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="custom-form invalid">
                            <input type="date" name="purchases_date" id="purchases_date" value="<?php echo date('Y-m-d'); ?>">
                            <label for="purchases_date">التاريخ</label>
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
                    <div class="col-md-3">
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
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label for="section_id" class="select-label">القسم</label>
                        <select class="form-select" name="section_id" id="section_id">
                            <option selected disabled>اختر القسم</option>

                        </select>
                    </div>
                </div>
                <hr />
                <div class="row align-items-end" style="margin-top: -1rem;">
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
                <table class="table table-light table-striped text-center table-exchange">
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
                        <th class="sumFinal"> </th>
                        <th></th>
                    </tfoot>
                </table>
            </div>
            <div class="d-grid gap-2 col-md-6 mx-auto mt-4 mb-2">
                <button class='btn btn-success fs-6' id="save_purchases">حفظ</button>
                <button class='btn btn-primary fs-6 d-none' id="update_purchases">تعديل</button>
            </div>
        </div>
    </section>
    @include('includes.stock.Stock_Ajax.exchange')
@endsection
