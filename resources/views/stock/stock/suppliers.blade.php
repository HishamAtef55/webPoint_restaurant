@php $title='الموردين';@endphp
@extends('layouts.stock.app')
@section('content')
    <section class='store pt-2'>
        <div class="container">
            @CSRF
            <div class='row'>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">رقم المورد</label>
                        <input type="text" class="form-control" name="supplier_id" id="supplier_id" value="{{$new_supplier}}" disabled>
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="supplier_name" class="form-label">اسم المورد</label>
                        <input type="text" class="form-control" name="supplier_name" id="supplier_name">
                        <ul class="search-result"></ul>
                    </div>
                    <div class="mb-3">
                        <label for="supplier_phone" class="form-label">رقم الهاتف</label>
                        <input type="text" class="form-control" name="supplier_phone" id="supplier_phone">
                    </div>
                    <div class="mb-3">
                        <label for="supplier_address" class="form-label">عنوان المورد</label>
                        <input type="text" class="form-control" name="supplier_address" id="supplier_address">
                    </div>

                    <div class="d-grid gap-2 col-md-6 mx-auto mt-4">
                        <button class='btn btn-success' id="save_supplier">Save</button>
                        <button class='btn btn-primary d-none' id="update_supplier">Update</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-light shadow text-center">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">الاسم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplires as $supplire)
                                <tr>
                                    <th scope="row">{{$supplire->id}}</th>
                                    <td>{{$supplire->name}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('includes.stock.Stock_Ajax.suppliers')
@endsection
