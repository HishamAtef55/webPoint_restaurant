@php $title='المخازن';@endphp
@extends('layouts.stock.app')
@section('content')
    <section class='store'>
        <h2 class="page-title">{{ $title }}</h2>
        <div class="container">
            @CSRF
            <div class="row">
                <div class="col-md-6">
                    <div class="bg-light p-2 rounded shadow">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <div class="custom-form">
                                    <input type="text" name="store_id" id="store_id" value="{{ $new_store }}"
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
                        <button class='btn btn-success stores_btns' data-action="create"
                            >Save</button>
                        <button class='btn btn-primary d-none' id="update_store">Update</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive rounded">
                        <table class="table table-light text-center table-data">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">name</th>
                                    <th scope="col">phone</th>
                                    <th scope="col">address</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if ($stores->isNotEmpty())
                                    @foreach ($stores as $store)
                                        <tr>
                                            <th>{{ $store->id }}</th>
                                            <td>{{ $store->name }}</td>
                                            <td>{{ $store->phone ?? '-' }}</td>
                                            <td>{{ $store->address ?? '-' }}</td>
                                            <td>
                                                <button title="تعديل" class="btn btn-success stores_btns"
                                                    data-id="{{ $store->id }}" data-action="edit">

                                                    <i class="far fa-edit"></i>
                                                </button>

                                                <button title="عرض" data-id="{{ $store->id }}" data-action="view"
                                                    class="btn btn-primary stores_btns">

                                                    <i class="fa fa-eye" aria-hidden="true"></i>

                                                </button>
                                                <button class="btn btn-danger stores_btns" data-action="delete"
                                                    data-id="{{ $store->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>

                                        <td colspan="2"> لا يوجد مخازن </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('includes.stock.Stock_Ajax.stores')
@endsection
