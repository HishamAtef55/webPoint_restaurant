@php $title='المخازن';@endphp
@extends('layouts.app')
@section('content')
<section class='store pt-2'>
    <div class="container">
        @CSRF
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="store_id" class="form-label">رقم المخزن</label>
                    <input type="text" class="form-control" name="store_id" id="store_id" value="{{$new_store}}"
                        disabled>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3 position-relative">
                    <label for="store_name" class="form-label">اسم المخزن</label>
                    <input type="text" class="form-control" name="store_name" id="store_name">
                    <ul class="search-result"></ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="store_phone" class="form-label">تليفون</label>
                    <input type="text" class="form-control" name="store_phone" id="store_phone">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="store_address" class="form-label">العنوان</label>
                    <input type="text" class="form-control" name="store_address" id="store_address">
                </div>
            </div>
        </div>
        <hr>
        <h3>طريقة التخزين</h3>
        <table class="store-table">
            <tbody>
                <tr>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input method-check" type="checkbox" value="تجميد"
                                id="freeze_method" name="storage_method">
                            <label class="form-check-label" for="freeze_method">
                                تجميد
                            </label>
                        </div>
                    </td>
                    <td>
                        <select class="form-select unit">
                            <option selected disabled>اختر نوع الوحدة</option>
                            <option value="كيلو">كيلو</option>
                            <option value="لتر">لتر</option>
                            <option value="عدد">عدد</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="capacity" id="capacity" placeholder="السعة">
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input method-check" type="checkbox" value="تبريد" id="cool_method"
                                name="storage_method">
                            <label class="form-check-label" for="cool_method">
                                تبريد
                            </label>
                        </div>
                    </td>
                    <td>
                        <select class="form-select unit">
                            <option selected disabled>اختر نوع الوحدة</option>
                            <option value="كيلو">كيلو</option>
                            <option value="لتر">لتر</option>
                            <option value="عدد">عدد</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="capacity" id="capacity" placeholder="السعة">
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input method-check" type="checkbox" value="أرضية" id="floor_method"
                                name="storage_method">
                            <label class="form-check-label" for="floor_method">
                                أرضية
                            </label>
                        </div>
                    </td>
                    <td>
                        <select class="form-select unit">
                            <option selected disabled>اختر نوع الوحدة</option>
                            <option value="كيلو">كيلو</option>
                            <option value="لتر">لتر</option>
                            <option value="عدد">عدد</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="capacity" id="capacity" placeholder="السعة">
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input method-check" type="checkbox" value="أرفف" id="shelf_method"
                                name="storage_method">
                            <label class="form-check-label" for="shelf_method">
                                أرفف
                            </label>
                        </div>
                    </td>
                    <td>
                        <select class="form-select unit">
                            <option selected disabled>اختر نوع الوحدة</option>
                            <option value="كيلو">كيلو</option>
                            <option value="لتر">لتر</option>
                            <option value="عدد">عدد</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="capacity" id="capacity" placeholder="السعة">
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input method-check" type="checkbox" value="اخري" id="other_method"
                                   name="storage_method">
                            <label class="form-check-label" for="other_method">
                                اخري
                            </label>
                        </div>
                    </td>
                    <td>
                        <select class="form-select unit">
                            <option selected disabled>اختر نوع الوحدة</option>
                            <option value="كيلو">كيلو</option>
                            <option value="لتر">لتر</option>
                            <option value="عدد">عدد</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="capacity" id="capacity" placeholder="السعة">
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="d-grid gap-2 col-md-6 mx-auto mt-4">
            <button class='btn btn-success' id="save_store">Save</button>
            <button class='btn btn-primary d-none' id="update_store">Update</button>
        </div>

        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="table-responsive">
                    <table class="table table-light shadow text-center mt-3">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">name</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($stores as $store)
                        <tr>
                            <th scope="row">{{$store->id}}</th>
                            <td>{{$store->name}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@include('includes.stock.Stock_Ajax.stores')
@endsection
