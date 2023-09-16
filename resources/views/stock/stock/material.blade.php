@php $title='الخامات';@endphp
@extends('layouts.app')
@section('content')
<div id="pageStatus" status="new"></div>
<section class='material pt-2'>
    <div class="container">
        @CSRF
        <div class='row'>
            <div class="col-lg-6 col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="main_group" class="form-label">المجموعة الرئيسية</label>
                            <select class="form-select" id="main_group">
                                <option selected disabled>اختر المجموعة الرئيسية</option>
                                    @foreach($mainGroup as $group)
                                        <option value="{{$group->id}}">{{$group->name}}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sub_group" class="form-label">المجموعة الفرعية</label>
                            <select class="form-select" id="sub_group">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3 position-relative">
                            <label for="material_id" class="form-label">كود الخامة</label>
                            <input type="text" class="form-control" name="material_id" id="material_id" value="1" disabled>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="mb-3 position-relative">
                            <label for="material_name" class="form-label">اسم الخامة</label>
                            <input type="text" class="form-control" name="material_name" id="material_name" value="">
                            <ul class="search-result"></ul>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="standard_cost" class="form-label">التكلفة المعيارية</label>
                            <input type="number" class="form-control" name="standard_cost" id="standard_cost" value="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="price" class="form-label">السعر</label>
                            <input type="number" class="form-control" name="price" id="price" value="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="unit" class="form-label">وحدة القياس</label>
                            <select class="form-select" id="unit">
                                <option selected disabled>اختر وحدة القياس</option>
                                    @foreach($units as $unit)
                                    <option value="{{$unit->name}}">{{$unit->name}}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="loss_ratio" class="form-label">نسبة الفقد</label>
                            <input type="number" class="form-control" name="loss_ratio" id="loss_ratio" value="">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="store_limit_min" class="form-label">حد طلب المخزن (min)</label>
                                    <input type="number" class="form-control" name="store_limit_min" id="store_limit_min" value="">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="store_limit_max" class="form-label">حد طلب المخزن (max)</label>
                                    <input type="number" class="form-control" name="store_limit_max" id="store_limit_max" value="">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="section_limit_min" class="form-label">حد طلب القسم (min)</label>
                                    <input type="number" class="form-control" name="section_limit_min" id="section_limit_min" value="">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="section_limit_max" class="form-label">حد طلب القسم (max)</label>
                                    <input type="number" class="form-control" name="section_limit_max" id="section_limit_max" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="store_method" class="form-label">نوع التخزين</label>
                            <select class="form-select" id="store_method">
                                <option selected disabled>اختر نوع التخزين</option>
                                    <option value="تجميد">تجميد</option>
                                    <option value="تبريد">تبريد</option>
                                    <option value="أرضية">أرضية</option>
                                    <option value="أرفف">أرفف</option>
                                    <option value="اخري">اخري</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="expire" class="form-label">مدة الصلاحية</label>
                            <input type="number" class="form-control" name="expire" id="expire" value="">
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1"
                                id="daily_inventory" name="daily_inventory">
                            <label class="form-check-label" for="daily_inventory">
                                جرد يومى
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1"
                                   id="packing" name="packing">
                            <label class="form-check-label" for="packing">
                               باكدج
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1"
                                id="all_group" name="all_group">
                            <label class="form-check-label" for="all_group">
                                جميع المجموعات
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1"
                                   id="manfu" name="manfu">
                            <label class="form-check-label" for="manfu">
                                خامة مصنعة
                            </label>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <h5>الفروع</h5>
                    @foreach($branchs as $branch)
                        <div class="form-check my-2">
                            <input class="form-check-input" type="checkbox" value="{{$branch->id}}" id="branch_{{$branch->id}}" name="branch">
                            <label class="form-check-label" for="branch_{{$branch->id}}"> {{$branch->name}} </label>
                        </div>
                        <div class="branch-sections bg-success bg-opacity-25 rounded-3 d-none"></div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6 col-md-4 position-relative">
                <div class="table-responsive mb-2 position-absolute w-75" style="max-height:100%">
                    <table class="table table-light shadow text-center all_data_table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">المجموعة الفرعية</th>
                            <th scope="col">الخامة</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($materials as $group)
                            <tr>
                                <th scope="row">{{$group->code}}</th>
                                <td>{{$group->group->name}}</td>
                                <td>{{$group->name}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-grid gap-2 col-md-6 mx-auto mt-4">
                <button class='btn btn-success' id="save_material">Save</button>
                <button class='btn btn-primary d-none' id="update_material">Update</button>
            </div>

        </div>
    </div>
</section>
@include('includes.stock.Stock_Ajax.material')
@endsection
