@php $title='الخامات';@endphp
@extends('layouts.stock.app')
@section('content')
    <section>
        <h2 class="page-title">{{ $title }}</h2>
        <div class="container">
            <div class='row'>
                <div class="col-lg-7 col-md-8">
                    <form action="" id="storeMaterial">
                        <div class="bg-light p-2 rounded shadow">
                            <div class="row align-items-end">
                                <div class="col">
                                    <div class="custom-form position-relative">
                                        <input type="text" name="id" id="id" value="{{ $lastMaterialNr }}"
                                            disabled>
                                        <label for="id">رقم الخامة</label>
                                    </div>
                                </div>
                                <div class="col flex-grow-1">
                                    <div>
                                        <label for="main_group" class="select-label">المجموعة الرئيسية</label>
                                        <select id="main_group" name="main_group_id">
                                            <option selected disabled>اختر المجموعة الرئيسية</option>
                                            @foreach ($mainGroup as $group)
                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col flex-grow-1">
                                    <div>
                                        <label for="sub_group" class="select-label">المجموعة الفرعية</label>
                                        <select disabled class="form-select" name="sub_group_id" id="sub_group">
                                            <option selected disabled>اختر المجموعة الفرعية</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr class="mb-0" />
                            <div class="row align-items-end">
                                <div class="col">
                                    <div class="custom-form position-relative">
                                        <input type="text" name="name" id="name">
                                        <label for="name">اسم الخامة</label>
                                        <ul class="search-result"></ul>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="custom-form">
                                        <input type="number" name="cost" id="cost">
                                        <label for="cost">التكلفة المعيارية</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="custom-form ">
                                        <input type="number" name="price" id="price">
                                        <label for="price">السعر</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div>
                                        <label for="unit" class="select-label">وحدة القياس</label>
                                        <select id="unit" name="unit">
                                            <option selected disabled>اختر وحدة القياس</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->value }}">{{ $unit->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr class="mb-0" />
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
                            <hr class="mb-0" />
                            <div class="row align-items-end">
                                <div class="col">
                                    <div class="custom-form mt-3">
                                        <input type="number" name="loss_ratio" id="loss_ratio">
                                        <label for="loss_ratio">نسبة الفقد</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div>
                                        <label for="storage_type" class="select-label">نوع التخزين</label>
                                        <select id="storage_type" name="storage_type">
                                            <option selected disabled>اختر نوع التخزين</option>
                                            @foreach ($storageTypes as $storageType)
                                                <option value="{{ $storageType->value }}">{{ $storageType->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="expire_date">مدة الصلاحية</label>
                                    <div class="custom-form">
                                        <input type="date" name="expire_date" id="expire_date">

                                    </div>
                                </div>
                            </div>
                            <hr class="mb-0" />
                            <div class="row">
                                @foreach ($materialTypes as $type)
                                    <div class="col-md-4 d-flex align-items-center mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input material-type" type="radio"
                                                value={{ $type->value }} id="{{ $type->value }}" name="materialType">
                                            <label class="form-check-label" for="{{ $type->value }}">
                                                {{ $type->toString() }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="bg-light p-2 mt-2 rounded shadow">
                            <div class="col">
                                <div>
                                    <label class="select-label" for="branch_id"> الفروع</label>
                                    <select name="branch_id" id="branch_id">
                                        <option selected disabled>اختر الفرع </option>
                                        @foreach ($branchs as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <hr class="mb-0" />
                            <h3>الأقسام</h3>
                            <div class="row section_id"
                                style="height:100px;
                                overflow-y: scroll;">

                                {{-- sections checkbox  --}}
                            </div>

                        </div>

                        <div class="d-grid gap-2  mt-3 mb-2">
                            <button class='btn btn-success' type="submit">حفظ</button>
                        </div>
                    </form>
                </div>

                <div class="col-lg-5 col-md-4">
                    <div class="table-responsive rounded" style="min-height: 420px">
                        <table class="table table-light text-center table-data tbody'">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">المجموعة الفرعية</th>
                                    <th scope="col">الخامة</th>
                                    <th>تحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($materials as $material)
                                    <tr id="sid{{ $material->id }}">
                                        <td>{{ $material->id }}</td>
                                        <td>{{ $material->group->name }}</td>
                                        <td>{{ $material->name }}</td>
                                        <td>
                                            <button title="تعديل" class="btn btn-success" data-id="{{ $material->id }}"
                                                id="edit_material">

                                                <i class="far fa-edit"></i>
                                            </button>

                                            <button title="عرض" data-id="{{ $material->id }}" id="view_material"
                                                class="btn btn-primary">

                                                <i class="fa fa-eye" aria-hidden="true"></i>

                                            </button>
                                            <button class="btn btn-danger" id="delete_material"
                                                data-id="{{ $material->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="4">لا يوجد خامات</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $materials->links() }}
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
                    <h5 class="modal-title" id="exampleModalLabel">عرض بيانات الخامة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="model-body">
                    <div class="bg-light p-2 rounded shadow">
                        <div class="row align-items-end">
                            <div class="col">
                                <div class="custom-form position-relative">
                                    <input type="text" name="id" id="edit_model_id"
                                        value="{{ $lastMaterialNr }}" disabled>
                                    <label for="view_model_id">رقم الخامة</label>
                                </div>
                            </div>
                            <div class="col flex-grow-1">
                                <div>
                                    <label for="view_model_main_group" class="select-label">المجموعة الرئيسية</label>
                                    <select id="view_model_main_group" name="main_group_id">

                                    </select>
                                </div>
                            </div>
                            <div class="col flex-grow-1">
                                <div>
                                    <label for="view_model_sub_group" class="select-label">المجموعة الفرعية</label>
                                    <select disabled class="form-select" name="sub_group_id" id="view_model_sub_group">
                                        <option selected disabled>اختر المجموعة الفرعية</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row align-items-end">
                            <div class="col">
                                <div class="custom-form position-relative">
                                    <input type="text" name="name" id="view_model_name">
                                    <label for="view_model_name">اسم الخامة</label>
                                    <ul class="search-result"></ul>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-form">
                                    <input type="number" name="cost" id="view_model_cost">
                                    <label for="view_model_cost">التكلفة المعيارية</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-form ">
                                    <input type="number" name="price" id="view_model_price">
                                    <label for="view_model_price">السعر</label>
                                </div>
                            </div>

                        </div>
                        <hr />
                        <div class="row">
                            <div class="col">
                                <div>
                                    <label for="view_model_unit" class="select-label">وحدة القياس</label>
                                    <select id="view_model_unit" name="unit">

                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-form  mt-4">
                                    <input type="number" name="store_limit_min" id="view_model_store_limit_min">
                                    <label for="view_model_store_limit_min"> طلب المخزن (min)</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-form mt-4">
                                    <input type="number" name="store_limit_max" id="view_model_store_limit_max">
                                    <label for="view_model_store_limit_max"> طلب المخزن (max)</label>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row align-items-end">
                            <div class="col">
                                <div class="custom-form mt-3">
                                    <input type="number" name="section_limit_min" id="view_model_section_limit_min">
                                    <label for="view_model_section_limit_min"> طلب القسم (min)</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-form mt-3">
                                    <input type="number" name="section_limit_max" id="view_model_section_limit_max">
                                    <label for="view_model_section_limit_max"> طلب القسم (max)</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-form mt-3">
                                    <input type="number" name="loss_ratio" id="view_model_loss_ratio">
                                    <label for="view_model_loss_ratio">نسبة الفقد</label>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row align-items-end">
                            <div class="col">
                                <div>
                                    <label for="view_model_storage_type" class="select-label">نوع التخزين</label>
                                    <select id="view_model_storage_type" name="storage_type">

                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-form mt-3">
                                    <input type="number" name="serial_nr" id="view_model_serial_nr">
                                    <label for="view_model_serial_nr"> الرقم التسلسلى</label>
                                </div>
                            </div>
                            <div class="col">
                                <label for="view_model_expire_date">مدة الصلاحية</label>
                                <div class="custom-form">
                                    <input type="date" name="expire_date" id="view_model_expire_date">

                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">

                            <div class="col-md-4 d-flex align-items-center mt-2">
                                <div class="form-check view_model_material_type">

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="bg-light p-2 mt-2 rounded shadow">
                        <div class="col">
                            <div>
                                <label class="select-label" for="view_model_branch_id"> الفروع</label>
                                <select name="branch_id" id="view_model_branch_id">
                                    <option selected disabled>اختر الفرع </option>
                                    @foreach ($branchs as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr />
                        <h3>الأقسام</h3>
                        <div class="row section_id"
                            style="height:100px;
                                overflow-y: scroll;">

                            {{-- sections checkbox  --}}
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
                    <h5 class="modal-title" id="exampleModalLabel">تعديل بيانات الخامة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="model-body">
                    <div class="bg-light p-2 rounded shadow">
                        <div class="row align-items-end">
                            <div class="col">
                                <div class="custom-form position-relative">
                                    <input type="text" name="id" id="edit_model_id"
                                        value="{{ $lastMaterialNr }}" disabled>
                                    <label for="edit_model_id">رقم الخامة</label>
                                </div>
                            </div>
                            <div class="col flex-grow-1">
                                <div>
                                    <label for="edit_model_main_group" class="select-label">المجموعة الرئيسية</label>
                                    <select id="edit_model_main_group" name="main_group_id">
                                        <option selected disabled>اختر المجموعة الرئيسية</option>
                                        @foreach ($mainGroup as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col flex-grow-1">
                                <div>
                                    <label for="edit_model_sub_group" class="select-label">المجموعة الفرعية</label>
                                    <select disabled class="form-select" name="sub_group_id" id="edit_model_sub_group">
                                        <option selected disabled>اختر المجموعة الفرعية</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr class="mb-0" />
                        <div class="row align-items-end">
                            <div class="col">
                                <div class="custom-form position-relative">
                                    <input type="text" name="name" id="edit_model_name">
                                    <label for="edit_model_name">اسم الخامة</label>
                                    <ul class="search-result"></ul>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-form">
                                    <input type="number" name="cost" id="edit_model_cost">
                                    <label for="edit_model_cost">التكلفة المعيارية</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-form ">
                                    <input type="number" name="price" id="edit_model_price">
                                    <label for="edit_model_price">السعر</label>
                                </div>
                            </div>

                        </div>
                        <hr class="mb-0" />
                        <div class="row">
                            <div class="col">
                                <div>
                                    <label for="edit_model_unit" class="select-label">وحدة القياس</label>
                                    <select id="edit_model_unit" name="unit">
                                        <option selected disabled>اختر وحدة القياس</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->value }}">{{ $unit->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-form mt-3">
                                    <input type="number" name="store_limit_min" id="edit_model_store_limit_min">
                                    <label for="edit_model_store_limit_min"> طلب المخزن (min)</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-form mt-3">
                                    <input type="number" name="store_limit_max" id="edit_model_store_limit_max">
                                    <label for="edit_model_store_limit_max"> طلب المخزن (max)</label>
                                </div>
                            </div>
                        </div>
                        <hr class="mb-0" />
                        <div class="row align-items-end">
                            <div class="col">
                                <div class="custom-form mt-3">
                                    <input type="number" name="section_limit_min" id="edit_model_section_limit_min">
                                    <label for="edit_model_section_limit_min"> طلب القسم (min)</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-form mt-3">
                                    <input type="number" name="section_limit_max" id="edit_model_section_limit_max">
                                    <label for="edit_model_section_limit_max"> طلب القسم (max)</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-form mt-3">
                                    <input type="number" name="loss_ratio" id="edit_model_loss_ratio">
                                    <label for="edit_model_loss_ratio">نسبة الفقد</label>
                                </div>
                            </div>
                        </div>
                        <hr class="mb-0" />
                        <div class="row align-items-end">
                            <div class="col">
                                <div>
                                    <label for="edit_model_storage_type" class="select-label">نوع التخزين</label>
                                    <select id="edit_model_storage_type" name="storage_type">
                                        <option selected disabled>اختر نوع التخزين</option>
                                        @foreach ($storageTypes as $storageType)
                                            <option value="{{ $storageType->value }}">{{ $storageType->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <label for="edit_model_expire_date">مدة الصلاحية</label>
                                <div class="custom-form">
                                    <input type="date" name="expire_date" id="edit_model_expire_date">

                                </div>
                            </div>
                        </div>
                        <hr class="mb-0" />
                        <div class="row">
                            @foreach ($materialTypes as $type)
                                <div class="col-md-4 d-flex align-items-center mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input material-type" type="radio"
                                            value={{ $type->value }} id="edit_model_{{ $type->value }}"
                                            name="materialType">
                                        <label class="form-check-label" for="edit_model_{{ $type->value }}">
                                            {{ $type->toString() }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="bg-light p-2 mt-2 rounded shadow">
                        <div class="col">
                            <div>
                                <label class="select-label" for="edit_model_branch_id"> الفروع</label>
                                <select name="branch_id" id="edit_model_branch_id">
                                    <option selected disabled>اختر الفرع </option>
                                    @foreach ($branchs as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr class="mb-0" />
                        <h3>الأقسام</h3>
                        <div class="row section_id"
                            style="height:100px;
                                overflow-y: scroll;">

                            {{-- sections checkbox  --}}
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="update_material">تعديل</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
    @include('includes.stock.Stock_Ajax.material')
@endsection
