@php $title='المجموعات الفرعية';@endphp
@extends('layouts.stock.app')
@section('content')
    <section>
        <h2 class="page-title">{{ $title }}</h2>
        <div class="container">
            <div class='row justify-content-center'>
                <div class="col-md-5">
                    <form id="storeSubGroup">
                        <div class="bg-light p-2 rounded shadow">

                            <div class="custom-form mt-3">
                                <input type="text" id="id" value="{{ $lastSubGroupNr }}" disabled>
                                <label for="id">رقم المجموعة الفرعية</label>
                            </div>

                            <div class="custom-form mt-3 position-relative">
                                <input type="text" id="name">
                                <label for="name">اسم المجموعة الفرعية</label>
                                {{-- <ul class="search-result"></ul> --}}
                            </div>

                            <div>
                                <label for="parent_group_id" class="select-label">المجموعه الرئيسية</label>
                                <select class="form-select" name="parent_group_id" id="parent_group_id">
                                    <option selected disabled>اختر المجموعه الرئيسية</option>
                                    @foreach ($mainGroups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="d-grid gap-2  mt-3">
                            <button class='btn btn-success' type="submit">حفظ</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-7">
                    <div class="table-responsive rounded" style="min-height: 420px">
                        <table class="table table-light text-center table-data">
                            <thead>
                                <tr>
                                    {{-- <th scope="col">#</th> --}}
                                    <th scope="col">المجموعة الرئيسية</th>
                                    <th scope="col">المجموعة الفرعية </th>
                                    <th scope="col">الرقم التسلسلي</th>
                                    <th scope="col">تحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subGroups as $group)
                                    <tr id="sid{{ $group->id }}">
                                        {{-- <td scope="row">{{ $group->id }}</td> --}}
                                        <td>{{ $group->parent->name }}</td>
                                        <td>{{ $group->name }}</td>
                                        <td>{{ $group->serial_nr }}</td>
                                        <td>
                                            <button title="تعديل" class="btn btn-success" data-id="{{ $group->id }}"
                                                id="edit_sub_group">

                                                <i class="far fa-edit"></i>
                                            </button>

                                            <button title="عرض" data-id="{{ $group->id }}" id="view_sub_group"
                                                class="btn btn-primary">

                                                <i class="fa fa-eye" aria-hidden="true"></i>

                                            </button>
                                            <button class="btn btn-danger" id="delete_sub_group"
                                                data-id="{{ $group->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="not-found">
                                        <td colspan="4"> لاتوجد مجوعات فرعية</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $subGroups->links() }}
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
                    <h5 class="modal-title" id="exampleModalLabel">عرض بيانات المجموعة الفرعية</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="model-body">
                    <div class="bg-light p-2 rounded shadow">
                        <div class="custom-form mt-3">
                            <input type="text" id="id" disabled>
                            <label for="id">رقم المجموعة الفرعية</label>
                        </div>

                        <div class="custom-form mt-3 position-relative">
                            <input type="text" id="name" disabled>
                            <label for="name">اسم المجموعة الفرعية</label>
                        </div>

                        <div class="custom-form mt-3 position-relative">
                            <input type="text" id="serialNr" disabled>
                            <label for="serialNr">الرقم التسلسلى</label>
                        </div>


                        <div>
                            <label for="parent_group_id" class="select-label">المجموعه الرئيسية</label>
                            <select class="form-select" name="parent_group_id" id="parent_group_id">
                            </select>
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
                    <h5 class="modal-title" id="exampleModalLabel">تعديل بيانات المجموعة الفرعية</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="model-body">

                    <div class="bg-light p-2 rounded shadow">
                        <div class="custom-form mt-3">
                            <input type="text" id="id" disabled>
                            <label for="id">رقم المجموعة الفرعية</label>
                        </div>

                        <div class="custom-form mt-3 position-relative">
                            <input type="text" id="name">
                            <label for="name">اسم المجموعة الفرعية</label>
                        </div>

                        <div class="custom-form mt-3 position-relative">
                            <input type="text" id="serialNr" disabled>
                            <label for="serialNr">الرقم التسلسلى</label>
                        </div>

                        <div>
                            <label for="parent_group_id" class="select-label">المجموعه الرئيسية</label>
                            <select class="form-select" name="parent_group_id" id="parent_group_id">
                                <option selected disabled>اختر المجموعه الرئيسية</option>
                                @foreach ($mainGroups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="update_sub_group">تعديل</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
    @include('includes.stock.Stock_Ajax.subGroups')
@endsection
