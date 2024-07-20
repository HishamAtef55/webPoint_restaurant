@php $title='الاقسام';@endphp
@extends('layouts.stock.app')
@section('content')
    <section>
        <h2 class="page-title">{{ $title }}</h2>
        <div class="container">
            <div class='row justify-content-center'>
                <div class="col-md-4">
                    <form id="storeSection">
                        <div class="bg-light p-2 rounded shadow">
                            <div class="custom-form mt-3">
                                <input type="text" name="section_id" id="section_id" value="{{ $lastSectionNr }}" disabled>
                                <label for="section_id">رقم القسم</label>
                            </div>
                            <div class="custom-form mt-3 position-relative">
                                <input type="text" name="section_name" id="section_name">
                                <label for="section_name">اسم القسم</label>
                            </div>
                            <div>
                                <label for="store" class="select-label">اسم المخزن</label>
                                <select class="form-select unit" name="store_id" id="store">
                                    <option selected disabled>اختر المخزن</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="branch" class="select-label">اسم الفرع</label>
                                <select class="form-select unit" name="branch_id" id="branch">
                                    <option selected disabled>اختر الفرع</option>
                                    @foreach ($branchs as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="bg-light p-2 rounded shadow mt-2">
                            <h3>المجموعات</h3>
                            <div class="groups"
                                style="height:100px;
                                overflow-y: scroll;  padding:5px;">
                            </div>
                        </div>

                        <div class="d-grid gap-2  mt-3 mb-2">
                            <button class='btn btn-success' type="submit">حفظ</button>
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
                                    <th scope="col">الفرع</th>
                                    <th scope="col">المخزن</th>
                                    <th scope="col">تحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sections as $section)
                                    <tr id="sid{{ $section->id }}">
                                        <td scope="row">{{ $section->id }}</td>
                                        <td>{{ $section->name }}</td>
                                        <td>{{ $section->branch->name }}</td>
                                        <td>{{ $section->store->name }}</td>
                                        <td>
                                            <button title="تعديل" class="btn btn-success" data-id="{{ $section->id }}"
                                                id="edit_section">

                                                <i class="far fa-edit"></i>
                                            </button>

                                            <button title="عرض" data-id="{{ $section->id }}" id="view_section"
                                                class="btn btn-primary">

                                                <i class="fa fa-eye" aria-hidden="true"></i>

                                            </button>
                                            <button class="btn btn-danger" id="delete_section"
                                                data-id="{{ $section->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">لا يوجد أقسام</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $sections->links() }}
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
                    <h5 class="modal-title" id="exampleModalLabel">عرض بيانات القسم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="model-body">
                    <div class="col-md-12">
                        <div class="bg-light p-2 rounded shadow">
                            <div class="custom-form mt-3">
                                <input type="text" id="id" disabled>
                                <label for="id">رقم القسم</label>
                            </div>
                            <div class="custom-form mt-3 position-relative">
                                <input type="text" id="name" disabled>
                                <label for="name">اسم القسم</label>
                            </div>
                            <div>
                                <label for="store_id" class="select-label">اسم المخزن</label>
                                <select class="form-select" id="store_id" name="store_id">
                                    <option selected disabled>اختر المخزن</option>
                                </select>
                            </div>
                            <div>
                                <label for="branch_id" class="select-label">اسم الفرع</label>
                                <select class="form-select" id="branch_id" name="branch_id">
                                    <option selected disabled>اختر الفرع</option>

                                </select>
                            </div>
                        </div>
                        <div class="bg-light p-2 rounded shadow mt-2">
                            <h3>المجموعات</h3>
                            <div class="groups">
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



    <!-- edit section odal -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تعديل بيانات القسم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="model-body">
                    <div class="col-md-12">
                        <div class="bg-light p-2 rounded shadow">
                            <div class="custom-form mt-3">
                                <input type="text" id="id" disabled>
                                <label for="id">رقم القسم</label>
                            </div>
                            <div class="custom-form mt-3 position-relative">
                                <input type="text" id="name">
                                <label for="name">اسم القسم</label>
                            </div>
                            <div>
                                <label for="store_id" class="select-label">اسم المخزن</label>
                                <select class="form-select" id="store_id" name="store_id">
                                    <option selected disabled>اختر المخزن</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="branch_id" class="select-label">اسم الفرع</label>
                                <select class="form-select" id="branch_id" name="branch_id">
                                    <option selected disabled>اختر الفرع</option>
                                    @foreach ($branchs as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="bg-light p-2 rounded shadow mt-2">
                            <h3>المجموعات</h3>
                            <div class="groups">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="update_section">تعديل</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
    @include('includes.stock.Stock_Ajax.sections')
@endsection
