@php $title='مكونات تفاصيل الاصناف';@endphp
@extends('layouts.app')
@section('content')
    <section class="component_items">
        <div class="container">
            @CSRF
            <div class="row">
                <div class="col-lg-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">اسم الفرع</label>
                                <select class="form-control select2" id="branch">
                                    <option disabled selected></option>
                                    @foreach($branchs as $branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="items"> اسم الصنف </label>
                                <select class="form-control select2 " id="items"></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="detailsItems"> تفاصيل الصنف </label>
                                <select class="form-control select2 " id="detailsItems"></select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="item_price" class="form-label">سعر البيع</label>
                                <input type="text" class="form-control item-price" name="item_price" id="item_price" disabled>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="product_qty" class="form-label">الكمية المنتجة</label>
                                <input type="number" class="form-control product-qty" min='1' value="1" name="product_qty" id="product_qty" >
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="select-box mb-2">
                                <label class="form-label"> المجموعة الرئيسية </label>
                                <select class="form-control select2" id="main_group">
                                    <option disabled selected></option>
                                    <option value="all">all</option>
                                    @foreach($groups as $group)
                                        <option value="{{$group->id}}">{{$group->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="materials">الخامة</label>
                                <select class="form-control select2" id="materials"></select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="unit" class="form-label" >الكمية ( <span id="unit_label"></span> )</label>
                                <input type="number" class="form-control unit" name="unit" id="unit">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="unit_price" class="form-label">سعر الوحدة</label>
                                <input type="number" class="form-control unit-price" name="unit_price" id="unit_price" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="table-responsive materials-responsive">
                        <table class="table table-light table-striped text-center table-materials">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>كود المكون</th>
                                <th>اسم المكون</th>
                                <th>الكمية</th>
                                <th>التكلفة</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot class="table-dark">
                            <tr>
                                <td>0</td>
                                <td>النسبة</td>
                                <td><input type="number" class="percentage" disabled> <span class="fs-5">%</span> </td>
                                <td>الاجمالى</td>
                                <td><input type="number" class="total-price" disabled></td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="d-grid gap-2 col-md-6 mx-auto mt-4">
                        <button class='btn btn-success  fs-4' id="save_component">Save</button>
                    </div>
                    <div class="d-flex flex-wrap gap-2 material-buttons justify-content-center mt-4">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transferModal">
                            تكرار مكونات الاصناف
                        </button>
                        <button id="DetailsWithoutMaterials" class="btn btn-warning">تفاصيل اصناف لا تحتوى على مكونات</button>
                        <button id="printDetails" class="btn btn-warning">طباعةمكونات صنف / تفاصيل صنف</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade transferModal" id="transferModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fw-bold" id="exampleModalLabel">تحويل المكونات</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label class="form-label">اسم الفرع</label>
                                <select class="form-control select2" id="fromBranch">
                                    <option disabled selected></option>
                                    @foreach($branchs as $branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="fromItems"> اسم الصنف </label>
                                <select class="form-control select2" id="fromItems"></select>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="fromDetailsItems"> تفاصيل الصنف </label>
                                    <select class="form-control select2 " id="fromDetailsItems"></select>
                                </div>
                            </div>
                            <ul class='fromComponents'></ul>
                        </div>
                        <div class="col-md-2 text-center align-self-center">
                            <button class="btn dark-btn transAll">TransAll</button>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label class="form-label">اسم الفرع</label>
                                <select class="form-control select2" id="toBranch">
                                    <option disabled selected></option>
                                    @foreach($branchs as $branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="toItems"> اسم الصنف </label>
                                <select class="form-control select2" id="toItems"></select>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="toDetailsItems"> تفاصيل الصنف </label>
                                    <select class="form-control select2 " id="toDetailsItems"></select>
                                </div>
                            </div>
                            <ul class='toComponents'></ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="save_transfer">حفظ</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade reportModal" id="reportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="fw-bold"  id="labelModel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" >
                    <div class="report_content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
    @include('includes.stock.Stock_Ajax.componentDetailsItems')
@stop
