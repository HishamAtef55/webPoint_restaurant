@php $title='المجموعات';@endphp
@extends('layouts.stock.app')
@section('content')
    <section>
        <h2 class="page-title">{{ $title }}</h2>
        <div class="container">
            <div class='row justify-content-center'>
                <div class="col-md-4">
                    <form id="storeMainGroup">
                        <div class="bg-light p-2 rounded shadow">
                            <div class="custom-form mt-3">
                                <input type="text" name="group_id" id="group_id" value="{{ $lastGroupNr }}" disabled>
                                <label for="group_id">رقم المجموعة </label>
                            </div>
                            <div class="custom-form mt-3 position-relative">
                                <input type="text" name="group_name" id="group_name">
                                <label for="group_name">اسم المجموعة </label>
                                <ul class="search-result"></ul>
                            </div>
                        </div>
                        <div class="d-grid gap-2  mt-3">
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
                                    <th scope="col">المجموعة</th>
                                    <th scope="col">تحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($groups as $group)
                                    <tr id="sid{{ $group->id }}">
                                        <td scope="row">{{ $group->id }}</td>
                                        <td>{{ $group->name }}</td>
                                        <td>
                                            <button title="تعديل" class="btn btn-success" data-id="{{ $group->id }}"
                                                id="edit_main_group">

                                                <i class="far fa-edit"></i>
                                            </button>

                                            <button title="عرض" data-id="{{ $group->id }}" id="view_main_group"
                                                class="btn btn-primary">

                                                <i class="fa fa-eye" aria-hidden="true"></i>

                                            </button>
                                            <button class="btn btn-danger" id="delete_main_group"
                                                data-id="{{ $group->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">لا توجد مجموعات رئيسية</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="model-body">

                    <div class="bg-light p-2 rounded shadow">
                        <div class="custom-form mt-3">
                            <input type="text" id="id" disabled>
                            <label for="id">رقم المجموعة </label>
                        </div>
                        <div class="custom-form mt-3 position-relative">
                            <input type="text" id="name" disabled>
                            <label for="name">اسم المجموعة </label>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="model-body">

                    <div class="bg-light p-2 rounded shadow">
                        <div class="custom-form mt-3">
                            <input type="text" id="id" disabled>
                            <label for="id">رقم المجموعة </label>
                        </div>
                        <div class="custom-form mt-3 position-relative">
                            <input type="text" id="name">
                            <label for="name">اسم المجموعة </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="update_main_group">تعديل</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
    @include('includes.stock.Stock_Ajax.mainGroups')
@endsection
