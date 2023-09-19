@php $title='الخامات';@endphp
@extends('layouts.stock.app')
@section('content')
<div id="pageStatus" status="new"></div>
<section>
    <h2 class="page-title">{{$title}}</h2>
    <div class="container">
        @CSRF
        <div class='row'>
            <div class="col-lg-7 col-md-8">
                <div class="bg-light p-2 rounded shadow">
                    <div class="row">
                        <div class="row">
                            <div class="col">
                                <div class="custom-form mt-3 position-relative">
                                    <input type="text" name="material_id" id="material_id" value="1" disabled>
                                    <label for="material_id">كود الخامة</label>
                                </div>
                            </div>
                            <div class="col flex-grow-1">
                                <div>
                                    <label for="main_group" class="select-label">المجموعة الرئيسية</label>
                                    <select id="main_group">
                                        <option selected disabled>اختر المجموعة الرئيسية</option>
                                            @foreach($mainGroup as $group)
                                                <option value="{{$group->id}}">{{$group->name}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col flex-grow-1">
                                <div>
                                    <label for="sub_group" class="select-label">المجموعة الفرعية</label>
                                    <select class="form-select" id="sub_group">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="col-md-5">
                            <div class="custom-form mt-3 position-relative">
                                <input type="text" name="material_name" id="material_name">
                                <label for="material_name">اسم الخامة</label>
                                <ul class="search-result"></ul>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="custom-form mt-3">
                                <input type="number" name="standard_cost" id="standard_cost">
                                <label for="standard_cost">التكلفة المعيارية</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="custom-form mt-3">
                                <input type="number" name="price" id="price">
                                <label for="price">السعر</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <label for="unit" class="select-label">وحدة القياس</label>
                                <select id="unit">
                                    <option selected disabled>اختر وحدة القياس</option>
                                        @foreach($units as $unit)
                                        <option value="{{$unit->name}}">{{$unit->name}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="custom-form mt-3">
                                <input type="number" name="loss_ratio" id="loss_ratio">
                                <label for="loss_ratio">نسبة الفقد</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col">
                                    <div class="custom-form mt-3">
                                        <input type="number" name="store_limit_min" id="store_limit_min">
                                        <label for="store_limit_min"> طلب المخزن (min)</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="custom-form mt-3">
                                        <input type="number" name="store_limit_max" id="store_limit_max">
                                        <label for="store_limit_max"> طلب المخزن (max)</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="custom-form mt-3">
                                        <input type="number" name="section_limit_min" id="section_limit_min">
                                        <label for="section_limit_min"> طلب القسم (min)</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="custom-form mt-3">
                                        <input type="number" name="section_limit_max" id="section_limit_max">
                                        <label for="section_limit_max"> طلب القسم (max)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div >
                                <label for="store_method" class="select-label">نوع التخزين</label>
                                <select id="store_method">
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
                            <div class="custom-form mt-3">
                                <input type="number" name="expire" id="expire">
                                <label for="expire">مدة الصلاحية</label>
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
                                <input class="form-check-input" type="checkbox" value="1" id="packing" name="packing">
                                <label class="form-check-label" for="packing"> باكدج </label>
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
                                <input class="form-check-input" type="checkbox" value="1" id="manfu" name="manfu">
                                <label class="form-check-label" for="manfu"> خامة مصنعة </label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="bg-light p-2 rounded shadow">
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
            </div>

            <div class="col-lg-5 col-md-4">
                <div class="table-responsive rounded" style="max-height: 442px">
                    <table class="table table-light text-center">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">المجموعة الفرعية</th>
                            <th scope="col">الخامة</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(count($materials) > 0)
                                @foreach($materials as $group)
                                    <tr>
                                        <th scope="row">{{$group->code}}</th>
                                        <td>{{$group->group->name}}</td>
                                        <td>{{$group->name}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">لا يوجد خامات</td>
                                </tr>
                            @endif
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
