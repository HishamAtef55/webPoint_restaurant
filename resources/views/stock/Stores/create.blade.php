@php $title='المخازن';@endphp
@extends('layouts.stock.app')
@section('content')
    <section class='store'>
        <h2 class="page-title">{{ $title }}</h2>
        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <form action="{{ route('stock.stores.store') }}" method="post">
                        @csrf
                        <div class="bg-light p-2 rounded shadow">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <div class="custom-form">
                                        <input type="text" value="{{ $lastStoreNr }}"disabled>
                                        <label for="store_id">رقم المخزن</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="custom-form position-relative">
                                        <input required type="text" name="name">
                                        <label for="name">اسم المخزن</label>
                                        @error('name')
                                            <span>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="custom-form">
                                        <input required type="text" name="phone">
                                        <label for="phone">تليفون</label>
                                        @error('phone')
                                            <span>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <div class="custom-form">
                                        <input type="text" name="address">
                                        <label for="address">العنوان</label>
                                        @error('address')
                                            <span>{{ $message }}</span>
                                        @enderror
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
                                                    name="type[]" id="freeze_type">
                                                <label class="form-check-label" for="freeze_type">
                                                    تجميد
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="freeze_unit" class="select-label">الوحدة</label>
                                            <select name="unit[]" class="form-select" id="freeze_unit">
                                                <option selected disabled>اختر نوع الوحدة</option>
                                                <option value="كيلو">كيلو</option>
                                                <option value="لتر">لتر</option>
                                                <option value="عدد">عدد</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="custom-form">
                                                <input type="text" class="form-control" name="capacity[]"
                                                    id="freeze_capacity">
                                                <label for="freeze_capacity"> السعة</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input method-check" type="checkbox" value="تبريد"
                                                    id="cool_type" name="type[]">
                                                <label class="form-check-label" for="cool_type">
                                                    تبريد
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="cool_unit" class="select-label">الوحدة</label>
                                            <select class="form-select" name="unit[]" id="cool_unit">
                                                <option selected disabled>اختر نوع الوحدة</option>
                                                <option value="كيلو">كيلو</option>
                                                <option value="لتر">لتر</option>
                                                <option value="عدد">عدد</option>
                                            </select>

                                        </td>
                                        <td>
                                            <div class="custom-form">
                                                <input type="text" class="form-control" name="capacity[]"
                                                    id="cool_capacity">
                                                <label for="cool_capacity"> السعة</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input method-check" type="checkbox" value="أرضية"
                                                    id="floor_type" name="type[]">
                                                <label class="form-check-label" for="floor_type">
                                                    أرضية
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="floor_unit" class="select-label">الوحدة</label>
                                            <select class="form-select" name="unit[]" id="floor_unit">
                                                <option selected disabled>اختر نوع الوحدة</option>
                                                <option value="كيلو">كيلو</option>
                                                <option value="لتر">لتر</option>
                                                <option value="عدد">عدد</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="custom-form">
                                                <input type="text" class="form-control" name="capacity[]"
                                                    id="floor_capacity">
                                                <label for="floor_capacity"> السعة</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input method-check" type="checkbox"
                                                    value="أرفف" id="shelf_type" name="type[]">
                                                <label class="form-check-label" for="shelf_type">
                                                    أرفف
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="shelf_unit" class="select-label">الوحدة</label>
                                            <select name="unit[]" class="form-select" id="shelf_unit">
                                                <option selected disabled>اختر نوع الوحدة</option>
                                                <option value="كيلو">كيلو</option>
                                                <option value="لتر">لتر</option>
                                                <option value="عدد">عدد</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="custom-form">
                                                <input type="text" class="form-control" name="capacity[]"
                                                    id="shelf_capacity">
                                                <label for="shelf_capacity"> السعة</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input method-check" type="checkbox"
                                                    value="اخري" id="other_type" name="type[]">
                                                <label class="form-check-label" for="other_type">
                                                    اخري
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <label for="other_unit" class="select-label">الوحدة</label>
                                            <select class="form-select" name="unit[]" id="other_unit">
                                                <option selected disabled>اختر نوع الوحدة</option>
                                                <option value="كيلو">كيلو</option>
                                                <option value="لتر">لتر</option>
                                                <option value="عدد">عدد</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="custom-form">
                                                <input type="text" class="form-control" name="capacity[]"
                                                    id="other_capacity">
                                                <label for="other_capacity"> السعة</label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-grid gap-2 mx-auto mt-4 mb-2">
                            <button class="btn btn-success" type="submit">حفظ</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </section>
    @include('includes.stock.Stock_Ajax.public_function')
@endsection
