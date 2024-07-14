@php $title='المخازن';@endphp
@extends('layouts.stock.app')
@section('content')
    <section class='store'>
        <h2 class="page-title">{{ $title }}</h2>
        <div class="container">
            <div class="row">
                <div class="col-md-6" id="storeModal">
                    <div class="bg-light p-2 rounded shadow">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <div class="custom-form">
                                    <input type="text" name="store_id" id="store_id" value="{{ $lastStoreNr }}"
                                        disabled>
                                    <label for="store_id">رقم المخزن</label>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="custom-form position-relative">
                                    <input type="text" name="store_name" id="store_name">
                                    <label for="store_name">اسم المخزن</label>
                                    <ul class="search-result"></ul>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="custom-form">
                                    <input type="text" name="store_phone" id="store_phone">
                                    <label for="store_phone">تليفون</label>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="custom-form">
                                    <input type="text" name="store_address" id="store_address">
                                    <label for="store_address">العنوان</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-light p-2 rounded shadow mt-4">
                        <!-- <h4>طريقة التخزين</h4> -->
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
                                        <label for="freeze" class="select-label">الوحدة</label>
                                        <select class="form-select unit" id="freeze">
                                            <option selected disabled>اختر نوع الوحدة</option>
                                            <option value="كيلو">كيلو</option>
                                            <option value="لتر">لتر</option>
                                            <option value="عدد">عدد</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="custom-form">
                                            <input type="text" class="form-control" name="capacity" id="freeze_capacity">
                                            <label for="freeze_capacity"> السعة</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input method-check" type="checkbox" value="تبريد"
                                                id="cool_method" name="storage_method">
                                            <label class="form-check-label" for="cool_method">
                                                تبريد
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <label for="cool" class="select-label">الوحدة</label>
                                        <select class="form-select unit" id="cool">
                                            <option selected disabled>اختر نوع الوحدة</option>
                                            <option value="كيلو">كيلو</option>
                                            <option value="لتر">لتر</option>
                                            <option value="عدد">عدد</option>
                                        </select>

                                    </td>
                                    <td>
                                        <div class="custom-form">
                                            <input type="text" class="form-control" name="capacity" id="cool_capacity">
                                            <label for="cool_capacity"> السعة</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input method-check" type="checkbox" value="أرضية"
                                                id="floor_method" name="storage_method">
                                            <label class="form-check-label" for="floor_method">
                                                أرضية
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <label for="floor" class="select-label">الوحدة</label>
                                        <select class="form-select unit" id="floor">
                                            <option selected disabled>اختر نوع الوحدة</option>
                                            <option value="كيلو">كيلو</option>
                                            <option value="لتر">لتر</option>
                                            <option value="عدد">عدد</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="custom-form">
                                            <input type="text" class="form-control" name="capacity" id="floor_capacity">
                                            <label for="floor_capacity"> السعة</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input method-check" type="checkbox" value="أرفف"
                                                id="shelf_method" name="storage_method">
                                            <label class="form-check-label" for="shelf_method">
                                                أرفف
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <label for="shelf" class="select-label">الوحدة</label>
                                        <select class="form-select unit" id="shelf">
                                            <option selected disabled>اختر نوع الوحدة</option>
                                            <option value="كيلو">كيلو</option>
                                            <option value="لتر">لتر</option>
                                            <option value="عدد">عدد</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="custom-form">
                                            <input type="text" class="form-control" name="capacity"
                                                id="shelf_capacity">
                                            <label for="shelf_capacity"> السعة</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input method-check" type="checkbox" value="اخري"
                                                id="other_method" name="storage_method">
                                            <label class="form-check-label" for="other_method">
                                                اخري
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <label for="other" class="select-label">الوحدة</label>
                                        <select class="form-select unit" id="other">
                                            <option selected disabled>اختر نوع الوحدة</option>
                                            <option value="كيلو">كيلو</option>
                                            <option value="لتر">لتر</option>
                                            <option value="عدد">عدد</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="custom-form">
                                            <input type="text" class="form-control" name="capacity"
                                                id="other_capacity">
                                            <label for="other_capacity"> السعة</label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-grid gap-2 mx-auto mt-4">
                        <button class='btn btn-success mb-2' id="save_store">حفظ</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive rounded">
                        <table class="table table-light text-center table-data">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">الإسم</th>
                                    <th scope="col">رقم التليفون</th>
                                    <th scope="col">العنوان</th>
                                    <th scope="col">تحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($stores->isNotEmpty())
                                    @foreach ($stores as $store)
                                        <tr id="sid{{ $store->id }}">
                                            <td>{{ $store->id }}</td>
                                            <td>{{ $store->name }}</td>
                                            <td>{{ $store->phone ?? '-' }}</td>
                                            <td>{{ $store->address ?? '-' }}</td>
                                            <td>
                                                <button title="تعديل" class="btn btn-success"
                                                    data-id="{{ $store->id }}" id="edit_store">

                                                    <i class="far fa-edit"></i>
                                                </button>

                                                <button title="عرض" data-id="{{ $store->id }}" id="view_store"
                                                    class="btn btn-primary">

                                                    <i class="fa fa-eye" aria-hidden="true"></i>

                                                </button>
                                                <button class="btn btn-danger" id="delete_store"
                                                    data-id="{{ $store->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>

                                        <td colspan="5"> لا يوجد مخازن </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $stores->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- view store odal -->
    <div class="modal fade" id="viewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="model-body">
                    <div class="col-md-12">
                        <div class="bg-light p-2 rounded shadow">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <div class="custom-form">
                                        <input type="text" name="id" id="id" disabled>
                                        <label for="id">رقم المخزن</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="custom-form position-relative">
                                        <input type="text" name="name" id="name">
                                        <label for="name">اسم المخزن</label>
                                        <ul class="search-result"></ul>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="custom-form">
                                        <input type="text" name="phone" id="phone">
                                        <label for="phone">تليفون</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="custom-form">
                                        <input type="text" name="address" id="address">
                                        <label for="address">العنوان</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="bg-light p-2 rounded shadow mt-4">
                                <!-- <h4>طريقة التخزين</h4> -->
                                <table class="store-table">
                                    <table class="store-table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input method-check" type="checkbox"
                                                            value="تجميد" id="view_freeze_method" name="storage_method">
                                                        <label class="form-check-label" for="view_freeze_method">
                                                            تجميد
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <label for="view_freeze_unit" class="select-label">الوحدة</label>
                                                    <select class="form-select unit" id="view_freeze_unit"
                                                        name="storage_unit">
                                                        <option selected disabled>اختر نوع الوحدة</option>
                                                        <option value="كيلو">كيلو</option>
                                                        <option value="لتر">لتر</option>
                                                        <option value="عدد">عدد</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="custom-form">
                                                        <input type="text" class="form-control" name="capacity"
                                                            id="view_freeze_capacity">
                                                        <label for="view_freeze_capacity"> السعة</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input method-check" type="checkbox"
                                                            value="تبريد" id="view_cool_method" name="storage_method">
                                                        <label class="form-check-label" for="view_cool_method">
                                                            تبريد
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <label for="view_cool_unit" class="select-label">الوحدة</label>
                                                    <select class="form-select unit" id="view_cool_unit"
                                                        name="storage_unit">
                                                        <option selected disabled>اختر نوع الوحدة</option>
                                                        <option value="كيلو">كيلو</option>
                                                        <option value="لتر">لتر</option>
                                                        <option value="عدد">عدد</option>
                                                    </select>

                                                </td>
                                                <td>
                                                    <div class="custom-form">
                                                        <input type="text" class="form-control" name="capacity"
                                                            id="view_cool_capacity">
                                                        <label for="view_cool_capacity"> السعة</label>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input method-check" type="checkbox"
                                                            value="أرضية" id="view_floor_method" name="storage_method">
                                                        <label class="form-check-label" for="view_floor_method">
                                                            أرضية
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <label for="view_floor_unit" class="select-label">الوحدة</label>
                                                    <select class="form-select unit" name="storage_unit"
                                                        id="view_floor_unit">
                                                        <option selected disabled>اختر نوع الوحدة</option>
                                                        <option value="كيلو">كيلو</option>
                                                        <option value="لتر">لتر</option>
                                                        <option value="عدد">عدد</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="custom-form">
                                                        <input type="text" class="form-control" name="capacity"
                                                            id="view_floor_capacity">
                                                        <label for="view_floor_capacity"> السعة</label>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input method-check" type="checkbox"
                                                            value="أرفف" id="view_shelf_method" name="storage_method">
                                                        <label class="form-check-label" for="view_shelf_method">
                                                            أرفف
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <label for="view_shelf_unit" class="select-label">الوحدة</label>
                                                    <select class="form-select unit" name="storage_unit"
                                                        id="view_shelf_unit">
                                                        <option selected disabled>اختر نوع الوحدة</option>
                                                        <option value="كيلو">كيلو</option>
                                                        <option value="لتر">لتر</option>
                                                        <option value="عدد">عدد</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="custom-form">
                                                        <input type="text" class="form-control" name="capacity"
                                                            id="view_shelf_capacity">
                                                        <label for="view_shelf_capacity"> السعة</label>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input method-check" type="checkbox"
                                                            value="اخري" id="view_other_method" name="storage_method">
                                                        <label class="form-check-label" for="view_other_method">
                                                            اخري
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <label for="view_other_unit" class="select-label">الوحدة</label>
                                                    <select class="form-select unit" name="storage_unit"
                                                        id="view_other_unit">
                                                        <option selected disabled>اختر نوع الوحدة</option>
                                                        <option value="كيلو">كيلو</option>
                                                        <option value="لتر">لتر</option>
                                                        <option value="عدد">عدد</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="custom-form">
                                                        <input type="text" class="form-control" name="capacity"
                                                            id="view_other_capacity">
                                                        <label for="view_other_capacity"> السعة</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>



    <!-- edit store odal -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="model-body">
                    <div class="bg-light p-2 rounded shadow">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <div class="custom-form">
                                    <input type="text" name="id" id="id" disabled>
                                    <label for="id">رقم المخزن</label>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="custom-form position-relative">
                                    <input type="text" name="name" id="name">
                                    <label for="name">اسم المخزن</label>
                                    <ul class="search-result"></ul>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="custom-form">
                                    <input type="text" name="phone" id="phone">
                                    <label for="phone">تليفون</label>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="custom-form">
                                    <input type="text" name="address" id="address">
                                    <label for="address">العنوان</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-light p-2 rounded shadow mt-4">

                        <!-- <h4>طريقة التخزين</h4> -->
                        <table class="store-table">
                            <table class="store-table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input method-check" type="checkbox"
                                                    value="تجميد" id="edit_freeze_method" name="storage_method">
                                                <label class="form-check-label" for="edit_freeze_method">
                                                    تجميد
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="edit_freeze_unit" class="select-label">الوحدة</label>
                                            <select class="form-select unit" id="edit_freeze_unit" name="storage_unit">
                                                <option selected disabled>اختر نوع الوحدة</option>
                                                <option value="كيلو">كيلو</option>
                                                <option value="لتر">لتر</option>
                                                <option value="عدد">عدد</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="custom-form">
                                                <input type="text" class="form-control" name="capacity"
                                                    id="edit_freeze_capacity">
                                                <label for="edit_freeze_capacity"> السعة</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input method-check" type="checkbox"
                                                    value="تبريد" id="edit_cool_method" name="storage_method">
                                                <label class="form-check-label" for="edit_cool_method">
                                                    تبريد
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="edit_cool_unit" class="select-label">الوحدة</label>
                                            <select class="form-select unit" id="edit_cool_unit" name="storage_unit">
                                                <option selected disabled>اختر نوع الوحدة</option>
                                                <option value="كيلو">كيلو</option>
                                                <option value="لتر">لتر</option>
                                                <option value="عدد">عدد</option>
                                            </select>

                                        </td>
                                        <td>
                                            <div class="custom-form">
                                                <input type="text" class="form-control" name="capacity"
                                                    id="edit_cool_capacity">
                                                <label for="edit_cool_capacity"> السعة</label>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input method-check" type="checkbox"
                                                    value="أرضية" id="edit_floor_method" name="storage_method">
                                                <label class="form-check-label" for="edit_floor_method">
                                                    أرضية
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="edit_floor_unit" class="select-label">الوحدة</label>
                                            <select class="form-select unit" name="storage_unit" id="edit_floor_unit">
                                                <option selected disabled>اختر نوع الوحدة</option>
                                                <option value="كيلو">كيلو</option>
                                                <option value="لتر">لتر</option>
                                                <option value="عدد">عدد</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="custom-form">
                                                <input type="text" class="form-control" name="capacity"
                                                    id="edit_floor_capacity">
                                                <label for="edit_floor_capacity"> السعة</label>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input method-check" type="checkbox"
                                                    value="أرفف" id="edit_shelf_method" name="storage_method">
                                                <label class="form-check-label" for="edit_shelf_method">
                                                    أرفف
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="edit_shelf_unit" class="select-label">الوحدة</label>
                                            <select class="form-select unit" name="storage_unit" id="edit_shelf_unit">
                                                <option selected disabled>اختر نوع الوحدة</option>
                                                <option value="كيلو">كيلو</option>
                                                <option value="لتر">لتر</option>
                                                <option value="عدد">عدد</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="custom-form">
                                                <input type="text" class="form-control" name="capacity"
                                                    id="edit_shelf_capacity">
                                                <label for="edit_shelf_capacity"> السعة</label>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input method-check" type="checkbox"
                                                    value="اخري" id="edit_other_method" name="storage_method">
                                                <label class="form-check-label" for="edit_other_method">
                                                    اخري
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="edit_other_unit" class="select-label">الوحدة</label>
                                            <select class="form-select unit" name="storage_unit" id="edit_other_unit">
                                                <option selected disabled>اختر نوع الوحدة</option>
                                                <option value="كيلو">كيلو</option>
                                                <option value="لتر">لتر</option>
                                                <option value="عدد">عدد</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="custom-form">
                                                <input type="text" class="form-control" name="capacity"
                                                    id="edit_other_capacity">
                                                <label for="edit_other_capacity"> السعة</label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </table>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="update_store">تعديل</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
    @include('includes.stock.Stock_Ajax.stores')
@endsection
