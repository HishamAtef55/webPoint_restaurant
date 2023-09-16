@php $title='تسعير اصناف';@endphp
@extends('layouts.app')
@section('content')

<section class="expenses">
    <div class="container">
        @CSRF
        <div class="bg-light p-4 mb-2 rounded shadow mb-3">
            <div class="row">
                <div class="col-md-4 mt-2">
                    <select id="branch">
                        <option value="all" disabled selected>All Branch</option>
                        @foreach($branchs as $row)
                            <option value="{{$row->id}}">{{$row->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mt-2">
                    <select id="group"></select>
                </div>
                <div class="col-md-4 mt-2">
                    <select id="subGroup"></select>
                </div>
                <div class="col-md-4 mt-2">
                    <select id="material">
                        <option value="all" selected>All Materials</option>
                        @foreach($materials as $row)
                            <option value="{{$row->code}}">{{$row->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mt-2">
                    <button class='btn btn-success fs-6 w-100' id="save_item_price">Search</button>
                </div>
                <!-- @foreach($inDirectCosts as $row)
                    <div class="col-md-4">
                        <div class="custom-form">
                            <label>{{$row->cost->name}}</label>
                            <input readonly value="{{$row->value}}">
                        </div>
                    </div>
                @endforeach -->
                <div class="row mt-3">
                    <div class="col-md-4 mt-2">
                        <div class="custom-form">
                            <label>Total</label>
                            <input type="number" value="{{$inDirectCostsSum}}" id="total">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="custom-form">
                            <label>Expected sale</label>
                            <input type="number" value="755811" id="expected_sale">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="custom-form">
                            <label>Indirect %</label>
                            <input type="text" value="50" id="indirect">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="custom-form">
                            <label>safe %</label>
                            <input type="text" value="10" id="safe">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive" dir="ltr">
            <table class="table table-light table-striped table-bordered text-center">
                <thead>
                    <tr>
                        <th rowspan="2"> Item Name </th>
                        <th rowspan="2"> Price </th>
                        <th rowspan="2"> Price Details </th>
                        <th rowspan="2"> Main Cost </th>
                        <th colspan="3"> PHASE ONE </th>
                        <th colspan="2"> PHASE TWO </th>
                        <th colspan="3"> PHASE THREE </th>
                        <th rowspan="2"> Cost % </th>
                        <th rowspan="2"> Profit % </th>
                    </tr>
                    <tr>
                        <th> Details Cost </th>
                        <th> Sauce Cost </th>
                        <th> Packing Cost </th>
                        <th> Undirect </th>
                        <th> Final Cost </th>
                        <th> Net Table </th>
                        <th> Net Take Away </th>
                        <th> Net Delivery </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</section>
@include('includes.reports_ajax.itemsPricing')
@endsection
