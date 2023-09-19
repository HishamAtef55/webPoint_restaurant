@php $title='الموردين';@endphp
@extends('layouts.stock.app')
@section('content')
<section>
    <h2 class="page-title">{{$title}}</h2>
        <div class="container">
            @CSRF
            <div class='row justify-content-center'>
                <div class="col-md-4">
                    <div class="bg-light p-2 rounded shadow">
                        <div class="custom-form mt-3">
                            <input type="text" name="supplier_id" id="supplier_id" value="{{$new_supplier}}" disabled>
                            <label for="supplier_id">رقم المورد</label>
                        </div>
                        <div class="custom-form mt-3 position-relative">
                            <input type="text" name="supplier_name" id="supplier_name">
                            <label for="supplier_name">اسم المورد</label>
                            <ul class="search-result"></ul>
                        </div>
                        <div class="custom-form mt-3">
                            <input type="text" name="supplier_phone" id="supplier_phone">
                            <label for="supplier_phone">رقم الهاتف</label>
                        </div>
                        <div class="custom-form mt-3">
                            <input type="text" name="supplier_address" id="supplier_address">
                            <label for="supplier_address">عنوان المورد</label>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button class='btn btn-success' id="save_supplier">Save</button>
                        <button class='btn btn-primary d-none' id="update_supplier">Update</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive rounded" style="min-height: 420px">
                        <table class="table table-light text-center">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">الاسم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($supplires) > 0)
                                    @foreach($supplires as $supplire)
                                    <tr>
                                        <th scope="row">{{$supplire->id}}</th>
                                        <td>{{$supplire->name}}</td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="2">لا يوجد موردين</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('includes.stock.Stock_Ajax.suppliers')
@endsection
