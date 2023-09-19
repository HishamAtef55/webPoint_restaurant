@php $title='التصنيع';@endphp
@extends('layouts.stock.app')
@section('content')
<section class="material_manufacturing">
    <h2 class="page-title">{{$title}}</h2>
    <div class="container">
        <div class="bg-light p-4 mb-2 rounded shadow">
            @CSRF
            <div class="row">
                <div class="col-md-4 mt-2">
                    <div class="custom-form">
                        <input type="number" value="{{$serial}}" name="manufacturing" id="manufacturing">
                        <label for="manufacturing">رقم التصنيعه</label>
                    </div>
                </div>
                <div class="col-md-4 mt-2">
                    <div class="custom-form">
                        <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d',strtotime("-1 days")); ?>">
                        <label for="date">التاريخ</label>
                    </div>
                </div>
                <div class="col-md-4 mt-2 text-start">
                    <button class='btn btn-primary w-50' id="new_manufacturing">جديد</button>
                </div>
            </div>
            <hr />
            <div class="row align-items-end" style="margin-top: -1rem;">
                <div class="col-md-3 branch-sec">
                    <label for="branch" class="select-label">الفرع</label>
                    <select class="form-select" id="branch">
                        <option selected disabled>اختر الفرع</option>
                        @foreach($branchs as $branch)
                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 branch-sec">
                    <label for="sections" class="select-label">الاقسام</label>
                    <select class="form-select" id="sections">
                        <option selected disabled>اختر القسم</option>
                    </select>
                </div>
                <div class="col-md-3 branch-sec">
                    <label for="component" class="select-label">المكون</label>
                    <select class="form-select" id="component">
                        <option selected disabled>اختر المكون</option>
                    </select>
                </div>
            </div>
            <hr />
            <div class="row align-items-center" style="margin-top: -1rem;">

                <div class="col">
                    <div class="custom-form">
                        <input type="number" name="price_comp" id="price_comp" disabled>
                        <label for="price_comp" >السعر</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-form">
                        <input type="number" name="quantity" id="quantity_comp">
                        <label for="quantity_comp" >الكمية ( <span class="quantity_unit"></span> )</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-form">
                        <input type="number" name="total" id="total" disabled>
                        <label for="total">الاجمالى</label>
                    </div>
                </div>
                <input type="hidden" name="total_dynamic" value="0">
                <input type="hidden" name="qty_dynamic" value="0">
                <div class="col">
                    <div class="custom-form">
                        <input type="number" name="available" id="available" disabled>
                        <label for="available">الرصيد</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-form">
                        <input type="number" name="halk" id="halk" disabled min="0">
                        <label for="halk">الهالك</label>
                    </div>
                </div>
                <div class="col">
                    <p class="mb-0">الكمية المصنعه : <span  class="fw-bold fs-5" id="qty_manufacture" style="color: rgba(var(--main-color), 1)"></span></p>
                </div>
            </div>
            <hr />
            <div class="row align-items-end variable-sec" style="margin-top: -1rem;">
                <div class="col-md-3">
                    <label for="material" class="select-label">الخامة</label>
                    <select class="form-select" id="material">
                        <option selected disabled>اختر الخامة</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input material-type" type="radio" value="static"
                            id="main_material" name="price_method" checked>
                        <label class="form-check-label" for="main_material">
                        خامة فرعية
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input material-type" type="radio" value="variable"
                            id="sub_material" name="price_method" >
                        <label class="form-check-label" for="sub_material">

                            خامة أساسية
                        </label>
                    </div>
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
                        <label for="price_material" >سعر الخامه الواحدة</label>
                    </div>
                </div>
                <div class="col">
                    <p class="mb-0"> السعر المتبقى للمكون: <span  class="fw-bold fs-5" id="price_manufacture" style="color: rgba(var(--main-color), 1)"></span></p>
                    <p class="mb-0"> اجمالى الخامة الثابته : <span  class="fw-bold fs-5" id="total_material" style="color: rgba(var(--main-color), 1)"></span></p>
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
                        <th>الكمية</th>
                        <th>الاجمالى</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="not-found">
                        <td colspan="5">لا يوجد بيانات</td>
                    </tr>
                </tbody>
                <tfoot class="table-dark">
                    <th colspan="4"> الاجمالي </th>
                    <th class="sumFinal">0</th>
            </tfoot>
            </table>
        </div>

        <div class="d-grid gap-2 col-md-6 mx-auto mt-4">
            <button class='btn btn-success fs-6' id="save_manufacturing" disabled>تصنيع</button>
            <div class="d-flex gap-2">
                <button class='btn btn-danger fs-6 d-none col' id="delete_manufacturing">مسح التصنيعه</button>
            </div>
        </div>

    </div>
</section>
@include('includes.stock.Stock_Ajax.material_manufacturing')
@stop
