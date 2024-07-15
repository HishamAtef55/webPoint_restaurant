@php $title='الموردين';@endphp
@extends('layouts.stock.app')
@section('content')
    <section>
        <h2 class="page-title">{{ $title }}</h2>
        <div class="container">
            <div class='row justify-content-center'>
                <div class="col-md-4">
                    <form id="storeSupplier" autocomplete="off">
                        <div class="bg-light p-2 rounded shadow">
                            <div class="custom-form mt-3">
                                <input type="text" name="id" id="supplier_id" value="{{ $lastSupplierNr }}" disabled>
                                <label for="supplier_id">رقم المورد</label>
                            </div>
                            <div class="custom-form mt-3 position-relative">
                                <input type="text" name="name" id="supplier_name">
                                <label for="supplier_name">اسم المورد</label>
                                <ul class="search-result"></ul>
                            </div>
                            <div class="custom-form mt-3">
                                <input type="text" name="phone" id="supplier_phone">
                                <label for="supplier_phone">رقم الهاتف</label>
                            </div>
                            <div class="custom-form mt-3">
                                <input type="text" name="address" id="supplier_address">
                                <label for="supplier_address">عنوان المورد</label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <button class="btn btn-success" type="submit">حفظ</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive rounded" style="min-height: 420px">
                        <table class="table table-light text-center table-data">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">الاسم</th>
                                    <th scope="col">الهاتف</th>
                                    <th scope="col">العنوان</th>
                                    <th scope="col">تحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($supplires as $supplire)
                                    <tr id="sid{{ $supplire->id }}">
                                        <td scope="row">{{ $supplire->id }}</td>
                                        <td>{{ $supplire->name ?? '-' }}</td>
                                        <td>{{ $supplire->phone ?? '-' }}</td>
                                        <td>{{ $supplire->address ?? '-' }}</td>
                                        <td>
                                            <button title="تعديل" class="btn btn-success" data-id="{{ $supplire->id }}"
                                                id="edit_supplier">

                                                <i class="far fa-edit"></i>
                                            </button>

                                            <button title="عرض" data-id="{{ $supplire->id }}" id="view_supplier"
                                                class="btn btn-primary">

                                                <i class="fa fa-eye" aria-hidden="true"></i>

                                            </button>
                                            <button class="btn btn-danger" id="delete_supplier"
                                                data-id="{{ $supplire->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">لا يوجد موردين</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $supplires->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- view section odal -->
    <div class="modal fade" id="viewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">عرض بيانات المورد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="model-body">
                    <div class="custom-form mt-3">
                        <input type="text" name="id" id="id" disabled>
                        <label for="id">رقم المورد</label>
                    </div>
                    <div class="custom-form mt-3 position-relative">
                        <input type="text" id="name" disabled>
                        <label for="name">اسم المورد</label>
                        <ul class="search-result"></ul>
                    </div>
                    <div class="custom-form mt-3">
                        <input type="text" id="phone" disabled>
                        <label for="phone">رقم الهاتف</label>
                    </div>
                    <div class="custom-form mt-3">
                        <input type="text" id="address" disabled>
                        <label for="address">عنوان المورد</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>



    <!-- edit section odal -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تعديل بيانات المورد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="model-body">
                        <div class="bg-light p-2 rounded shadow">
                            <div class="custom-form mt-3">
                                <input type="text" id="id"
                                    disabled>
                                <label for="id">رقم المورد</label>
                            </div>
                            <div class="custom-form mt-3 position-relative">
                                <input type="text"  id="name">
                                <label for="name">اسم المورد</label>
                                <ul class="search-result"></ul>
                            </div>
                            <div class="custom-form mt-3">
                                <input type="text" id="phone">
                                <label for="phone">رقم الهاتف</label>
                            </div>
                            <div class="custom-form mt-3">
                                <input type="text" id="address">
                                <label for="address">عنوان المورد</label>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="update_supplier">تعديل</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
    @include('includes.stock.Stock_Ajax.suppliers')
@endsection
