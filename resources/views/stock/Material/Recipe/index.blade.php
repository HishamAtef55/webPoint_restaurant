@php $title='مكونات الخامات';@endphp
@extends('layouts.stock.app')
@section('content')
    <section>
        <h2 class="page-title">{{ $title }}</h2>
        <div class="container">
            <div class="row">

                <div class="col-lg-5"  id="storeMaterialRecipe">
                        <div class="bg-light p-2 rounded shadow">
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <label class="select-label" for="_branch"> الفرع</label>
                                        <select id="_branch" name="branch_id">
                                            <option disabled selected>اختر الفرع </option>
                                            @forelse ($branchs as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>

                                            @empty
                                                <option value="">لاتوجد فروع متاحة</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div>
                                        <label class="select-label" for="_manufactured_material">اسم الخامة</label>
                                        <select id="_manufactured_material" name="manufactured_material_id">
                                            <option disabled selected>اختر الخامه</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="custom-form mt-3">
                                        <input type="text" name="material_price" value="0" id="_price" disabled>
                                        <label for="_price">سعر التكلفة</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="custom-form mt-3">
                                        <input type="number" min='1' value="1" name="product_qty"
                                            id="_product_qty">
                                        <label for="_product_qty">الكمية المنتجة</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="custom-form mt-3">
                                        <label for="_materials" class="select-label">الخامة</label>
                                        <select id="_materials" name="material_id">
                                            <option disabled selected>اختر الخامة</option>
                                            @forelse ($materials as $material)
                                                <option value="{{ $material->id }}"
                                                    data-unit="{{ $material->unit['sub_unit']['name_ar'] }}"
                                                    data-unit-name-en="{{ $material->unit['sub_unit']['name_en'] }}"
                                                    data-unit-value="{{ $material->unit['sub_unit']['value'] }}"
                                                    data-price="{{ $material->price ?? 0 }}">
                                                    {{ $material->name }}</option>

                                            @empty
                                                <option value="" data-unit="" data-price="">لاتوجد خامات متاحة
                                                </option>
                                            @endforelse

                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="custom-form mt-3">
                                        <input type="number" value="0" name="unit" id="_unit">
                                        <label for="_unit">الكمية ( <span id="unit_label"></span> )</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="custom-form mt-3">
                                        <input type="number" value="0" name="unit_price" id="unit_price" disabled>
                                        <label for="unit_price">سعر الوحدة</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <button class='btn btn-success' id="save_material_recipe">حفظ</button>
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
                                    <th>التكلفة</th>
                                    <th>الوحدة</th>
                                    <th>تحكم</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="not-found">
                                    <td colspan="8">لا يوجد بيانات</td>
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
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex flex-wrap gap-2 material-buttons justify-content-center mt-4">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#transferModal">
                            تكرار مكونات الخامات
                        </button>
                        <button id="print_components" class="btn btn-warning">طباعة المكونات</button>
                        <button id="print_component" class="btn btn-warning">طباعة مكون</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade transferModal" id="transferModal" tabindex="-1" aria-labelledby="exampleModalLabel"
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
                            <div class="custom-form mt-3">
                                <label for="fromItems">بيانات الخامات</label>
                                <select class="form-control select2" id="fromItems">
                                    <option disabled selected></option>
                                    @foreach ($materials as $material)
                                        <option data-price="{{ $material->price }}" value="{{ $material->code }}">
                                            {{ $material->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <ul class='fromComponents'></ul>
                        </div>
                        <div class="col-md-2 text-center align-self-center">
                            <button class="btn dark-btn transAll">TransAll</button>
                        </div>
                        <div class="col-md-5">
                            <div class="custom-form mt-3">
                                <label for="toItems"> اسم الخامات </label>
                                <select class="form-control select2" id="toItems">
                                    <option disabled selected></option>
                                    @foreach ($materials as $material)
                                        <option data-price="{{ $material->price }}" value="{{ $material->code }}">
                                            {{ $material->name }}</option>
                                    @endforeach
                                </select>
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

    <div class="modal fade reportModal" id="reportModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
    @include('includes.stock.Stock_Ajax.material_recipe')
@endsection
