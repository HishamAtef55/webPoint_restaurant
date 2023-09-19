@php $title='المصاريف الغير مباشرة';@endphp
@extends('layouts.stock.app')
@section('content')
<section class="expenses">
    <h2 class="page-title">{{$title}}</h2>
    <div class="container">
        @CSRF
        <div class="row">
            <div class="col-md-6">
                <div class="bg-light p-4 mb-2 rounded shadow mb-3">
                    <div class="d-flex gap-3">
                        <div class="custom-form flex-grow-1">
                            <input type="text" value="" name="expenses_name" id="expenses_name">
                            <label for="expenses_name">اسم المصروف</label>
                        </div>
                        <button class='btn btn-success fs-6' id="save_expenses">Save</button>
                    </div>
                </div>
                <div class="table-responsive expenses-name-responsive rounded">
                    <table class="table table-light table-striped text-center expenses_name_table table-purchases">
                        <thead>
                            <tr>
                                <th> اسم المصروف </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data) > 0)
                                @foreach($data as $one)
                                    <tr rowId="{{$one->id}}">
                                        <td>
                                            <input type="text" class="form-control" value="{{$one->name}}"/>
                                            <span>{{$one->name}}</span>
                                        </td>
                                        <td>
                                            <div class="del-edit">
                                                <button class="btn btn-danger delete_expenses"><i class="fa-regular fa-trash-can"></i></button>
                                                <button class="btn btn-warning edit_expenses"><i class="fa-regular fa-pen-to-square"></i></button>
                                            </div>
                                            <button class="btn btn-primary update_expenses update">Update</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <tr class="not-found">
                                <td colspan="2">لا يوجد بيانات</td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-light p-4 pt-2 mb-2 rounded shadow mb-3">
                    <div class="row align-items-end">
                        <div class="col-md-6 mb-3">
                            <div class="custom-form">
                                <input type="date"  name="date" id="date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d',strtotime("-1 days")); ?>">
                                <label for="date" >التاريخ</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="expenses" class="select-label">المصروف</label>
                            <select class="form-select" id="expenses">
                                <option selected disabled>المصروف</option>
                                @foreach($data as $one)
                                    <option value="{{$one->id}}">{{$one->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="custom-form">
                                <input type="number"  name="expenses_price" id="expenses_price">
                                <label for="expenses_price" >المبلغ</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class='btn btn-block btn-success fs-6' id="add_expenses">Add</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive expenses-responsive rounded">
                    <table class="table table-light table-striped text-center expenses_table table-purchases">
                        <thead>
                            <tr>
                                <th> اسم المصروف </th>
                                <th> التاريخ </th>
                                <th> المبلغ </th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($inDirectCosts) > 0)
                            @foreach($inDirectCosts as $row)
                                <tr rowId="{{$row->id}}">
                                    <td>{{$row->cost->name}}</td>
                                    <td>{{$row->date}}</td>
                                    <td>{{$row->value}}</td>
                                    <td>
                                        <div class="del-edit">
                                            <button class="btn btn-danger delete_expenses"><i class="fa-regular fa-trash-can"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                        <tr class="not-found">
                            <td colspan="4">لا يوجد بيانات</td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@include('includes.stock.Stock_Ajax.indirect_cost')
@endsection
