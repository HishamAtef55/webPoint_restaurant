@php $title='التشغيل';@endphp
@extends('layouts.stock.app')
@section('content')
<section class="material_operations">
    <h2 class="page-title">{{$title}}</h2>
    <div class="container">
        <div class="bg-light p-4 mb-2 rounded shadow">
            @CSRF
            <div class="row">
                <div class="col-md-4 mt-2">
                    <div class="custom-form">
                        <input type="number" value="{{$serial}}" name="operation" id="operation">
                        <label for="operation">رقم التشغيل</label>
                    </div>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="custom-form">
                        <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d',strtotime("-1 days")); ?>">
                        <label for="date">التاريخ</label>
                    </div>
                </div>
                <div class="col-md-4 mt-2 text-start">
                    <button class='btn btn-primary w-50' id="new_operation">جديد</button>
                </div>
            </div>
            <hr />
            <div class="row align-items-end" style="margin-top: -1rem;">
                <div class="col-md-1">
                    <div class="form-check">
                        <input class="form-check-input operations-method" type="radio" value="static"
                            id="static_method" name="operations_method" checked>
                        <label class="form-check-label" for="static_method">
                            ثابت
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input operations-method" type="radio" value="variable"
                            id="variable_method" name="operations_method">
                        <label class="form-check-label" for="variable_method">
                            متغير
                        </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="branch" class="select-label">الفرع</label>
                    <select class="form-select" id="branch">
                        <option selected disabled>اختر الفرع</option>
                        @foreach($branchs as $branch)
                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="sections" class="select-label">الاقسام</label>
                    <select class="form-select" id="sections">
                        <option selected disabled>اختر القسم</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="components" class="select-label">المكونات</label>
                    <select class="form-select" id="components">
                        <option selected disabled>اختر المكون</option>
                    </select>
                </div>
                <div class="col">
                    <div class="custom-form">
                        <input type="number" name="price_comp" id="price_comp" disabled>
                        <label for="price_comp" >السعر</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-form">
                        <input type="number" name="quantity" id="quantity_comp">
                        <label for="quantity_comp" >المقدار</label>
                    </div>
                </div>
            </div>
            <hr class="variable-sec d-none" />
            <div class="row align-items-end variable-sec d-none" style="margin-top: -1rem;">
                <div class="col-md-3">
                    <label class="select-label"> المجموعة الرئيسية </label>
                    <select class="form-select" id="main_group">
                        <option disabled selected>اختر المجموعة</option>
                        <option value="all">all</option>
                        <option value="1">طعام</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="material" class="select-label">الخامة</label>
                    <select class="form-select" id="material">
                        <option disabled selected>اختر الخامة</option>
                    </select>
                </div>
                <div class="col">
                    <div class="custom-form">
                        <input type="number" name="quantity_material" id="quantity_material">
                        <label for="quantity_material" >الكمية ( <span id="unit_label"></span> )</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-form">
                        <input type="number" name="price_material" id="price_material" disabled>
                        <label for="price_material">سعر الوحدة</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive materials-responsive">
            <table class="table table-light table-striped text-center table-materials">
                <thead>
                    <tr>
                        <th>كود المكون</th>
                        <th>اسم المكون</th>
                        <th>السعر</th>
                        <th>المقدار</th>
                        <th>المقدرا المتاح</th>
                        <th>الاجمالى</th>
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
                    <th class="sumFinal">0</th>
                    <th></th>
            </tfoot>
            </table>
        </div>
        <div class="d-grid gap-2 col-md-6 mx-auto mt-4">
            <button class='btn btn-success fs-6' id="save_operation">تشغيل</button>
            <button class='btn btn-danger fs-6 d-none' id="delete_operation">مسح التشغيلة</button>
        </div>
    </div>
</section>

<div class="modal fade componentsModal" id="componentsModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="fw-bold"  id="labelModel"> مكونات الخامات </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h1> مكونات الخامات </h1>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</div>
{{-- @include('includes.stock.Stock_Ajax.material_operations') --}}
@stop
