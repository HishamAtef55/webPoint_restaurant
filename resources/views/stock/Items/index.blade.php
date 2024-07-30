@php $title='مكونات الاصناف';@endphp
@extends('layouts.stock.app')
@section('content')
    <section>
        <h2 class="page-title">{{ $title }}</h2>
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="bg-light p-2 rounded shadow">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="select-label">اسم الفرع</label>
                                <select id="branch">
                                    <option disabled selected>اختر الفرع</option>
                                    @foreach ($branchs as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="items" class="select-label">اسم الصنف</label>
                                <select class="form-control select2 " id="items">
                                    <option disabled selected>اختر الصنف</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-form mt-3">
                                    <input type="text" value="0" name="item_price" id="item_price" disabled>
                                    <label for="item_price">سعر البيع</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-form mt-3">
                                    <input type="number" class="product-qty" min='1' value="1"
                                        name="product_qty" id="product_qty">
                                    <label for="product_qty">الكمية المنتجة</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="materials" class="select-label">الخامة</label>
                                <select id="materials">
                                    <option disabled selected>اختر الخامة</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-form mt-3">
                                    <input type="number" class="unit" name="unit" id="unit">
                                    <label for="unit">الكمية ( <span id="unit_label"></span> )</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-form mt-3">
                                    <input type="number" value="0" name="unit_price" id="unit_price" disabled>
                                    <label for="unit_price">سعر الوحدة</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="d-grid gap-2 mt-3">
                        <button class='btn btn-success' id="save_component">حفظ</button>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="table-responsive materials-responsive rounded" style="min-height: 420px">
                        <table class="table table-light table-striped text-center table-materials">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>كود المكون</th>
                                    <th>اسم المكون</th>
                                    <th>الكمية</th>
                                    <th>الوحدة</th>
                                    <th>التكلفة</th>
                                    <th>تحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="not-found">
                                    <td colspan="7">لا يوجد بيانات</td>
                                </tr>
                            </tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <td></td>
                                    <td>النسبة</td>
                                    <td><input type="number" class="percentage" value="0" disabled> <span
                                            class="fs-5">%</span> </td>
                                    <td>الاجمالى</td>
                                    <td><input type="number" class="total-price" value="0" disabled></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex flex-wrap gap-2 material-buttons justify-content-center mt-4">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#transferModal">
                            تكرار مكونات الاصناف
                        </button>
                        <button id="itemWithOutMaterials" class="btn btn-warning">اصناف لا تحتوى على مكونات</button>
                        <button id="componentWithoutItems" class="btn btn-warning">مكون لا يحتوي علي اصناف</button>
                    </div>
                    <div class="d-flex flex-wrap gap-2 material-buttons justify-content-center mt-4">
                        <button id="print_components" class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#printComponentsModal">طباعة المكونات</button>
                        <button id="print_item" class="btn btn-warning">طباعة صنف</button>
                        <button id="print_component" class="btn btn-warning">طباعة مكون</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade transferModal" id="transferModal" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fw-bold" id="exampleModalLabel">تحويل المكونات</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <p class="text-center fw-bold">From</p>
                            <div class="custom-form mb-3">
                                <label class="form-label mb-1">اسم الفرع</label>
                                <select class="form-control select2" id="fromBranch">
                                    <option disabled selected></option>
                                    @foreach ($branchs as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="custom-form mb-3">
                                <label for="fromItems">بيانات الاصناف</label>
                                <select class="form-control select2" id="fromItems"></select>
                            </div>
                            <ul class='fromComponents'></ul>
                        </div>
                        <div class="col-md-2 text-center align-self-center">
                            <button class="btn dark-btn transAll">TransAll</button>
                        </div>
                        <div class="col-md-5">
                            <p class="text-center fw-bold">To</p>
                            <div class="custom-form mb-3">
                                <label class="form-label mb-1">اسم الفرع</label>
                                <select class="form-control select2" id="toBranch">
                                    <option disabled selected></option>
                                    @foreach ($branchs as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="custom-form mb-3">
                                <label for="toItems"> اسم الصنف </label>
                                <select class="form-control select2" id="toItems" multiple="multiple"></select>
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

    <div class="modal fade reportModal" id="reportModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="fw-bold" id="labelModel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="report_content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade printComponentsModal" id="printComponentsModal" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="fw-bold" id="labelModel">طباعة المكونات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="custom-form mt-3">
                                <label class="form-label">اسم الفرع</label>
                                <select class="form-control select2" id="components_branch">
                                    <option disabled selected></option>
                                    @foreach ($branchs as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="component_details">
                                <label class="form-check-label" for="component_details">مكونات تفاصيل الاصناف</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="group-content d-flex align-items-center flex-wrap mt-5"></div>
                            <div class="d-grid mb-2">
                                <button class="btn btn-primary" id="search_component_details">بحث</button>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="details-report"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>

    @include('includes.stock.Stock_Ajax.component_items')
@endsection
