@php
    $title = 'Pilot Account';
@endphp

@extends('layouts.menu')
@section('content')
    <!-- Start Delivery -->
    <section class='delivery'>
        <div id="check_page" value="pilot_account"></div>
        <div id="summary_hold"></div>
        <div id="new_order"  value=""></div>
        <div id="operation" value="Delivery"></div>
        <input type="hidden" id="device_id" value="">

        <div class="modal fade" id="pilot" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select Pilot</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-5 pb-5">
                        <form>
                            <div class="input-group">
                                <select  class="custom-select" id="pilot-select-modal">
                                    <option selected>Choose Pilot</option>
                                    @foreach($pilots as $pilot)
                                        <option value="{{$pilot->id}}" >{{$pilot->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" id="add_pilot" class="btn btn-success">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <div class='container'>
            @include('includes.menu.sub_header')
            <div class="modal-body d-flex flex-column-reverse flex-wrap">
                <div class='row w-100'>
                    <div class='col my-5 flex-wrap' id="box_content">
                        @php
                            $counter = 0;
                            $money   = 0;
                            $pilotValue = 0;
                        @endphp
                        @foreach($orders as $order)
                        @php
                            $counter++;
                            $money += $order->total;
                            $pilotValue += $order->locations->pilot_value;
                        @endphp
                            <div class='box pilot' box_id="{{$order->order_id}}">

                                <ul class="list-unstyled box-list">
                                    <li>
                                        <i class="fas fa-hashtag"></i>
                                        <span class="order_id">{{$order->order_id}}</span>
                                    </li>

                                    <li>
                                        <i class="fas fa-user"></i>
                                        <span>{{$order->customer_name}}</span>
                                    </li>

                                    <li>
                                        <i class="fas fa-biking fa-fw"></i>
                                        <span class="pilot-name">{{$order->pilot_name}}</span>
                                    </li>

                                    <li>
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{$order->locations->location}}</span>
                                    </li>
                                    <li class='orderPrice'>
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span>{{$order->total}}</span>
                                    </li>

                                </ul>

                                <div class='box-menu'>

                                    <ul>
                                        @csrf
                                        @can("change pilot")
                                        <li data-toggle="modal" data-target="#pilot">
                                            <a href="#">
                                                <i class="fas fa-biking fa-fw"></i>
                                                <span>Change Pilot</span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('pay check delivery')
                                        <li>
                                            <a href="#" class='done'>
                                                <i class="fas fa-check fa-fw"></i>
                                                <span> Pay </span>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="row w-100">
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                @csrf
                                <select id="select_location" class="custom-select">
                                    <option value="all" >All Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{$location['id']}}" >{{$location['location']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                @csrf
                                <select id="select_pilot" class="custom-select">
                                    <option value="all" >All Pilots</option>
                                    @foreach($pilots_o as $pilot)
                                        <option value="{{$pilot['id']}}" >{{$pilot['pilot']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="delivery-info d-flex justify-content-around">
                                <div>
                                    <span>عدد الطلبات</span>
                                    <span class="info-num ordersNum">{{$counter}}</span>
                                </div>
                                <div>
                                    <span>اجمالى المبلغ</span>
                                    <span class="info-num ordersPrice">{{$money}}</span>
                                </div>
                                <div>
                                    <span>عمولة الطيار</span>
                                    <span class="info-num pilotValue">{{$pilotValue}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </section>
    <!-- End Delivery -->
    <!-- End Box Model For Change Menus -->
    <div class="modal fade pay-modal" id="pay-model"  tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">PAY</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <section class='check-out'>
                        <div class='container'>
                            <div class='row'>
                                <div class="col-lg-4 col-md-5 col-6">
                                    <div class='summary'>
                                        <h2>Order Summary</h2>
                                        <ul class="list-unstyled">

                                            <li class='last-item'>
                                                <div>
                                                    <span>Items</span>
                                                    <span class="items-quant"></span>
                                                </div>
                                                <div class='total'>
                                                    <span>Sub Total</span>
                                                    <span class="summary-total"></span>
                                                </div>
                                                <div class='service'>
                                                    <span>Service</span>
                                                    <span class="summary-service"></span>
                                                </div>
                                                <div class='tax'>
                                                    <span>Tax</span>
                                                    <span class="summary-tax"></span>
                                                </div>
                                                <div class='bank'>
                                                    <span>Bank Value</span>
                                                    <span class="summary-bank">0.00</span>
                                                </div>
                                                <div class='min-charge'>
                                                    <span>Min-Charge</span>
                                                    <span class="summary-mincharge"></span>
                                                </div>
                                                <div class='discount'>
                                                    <span>Discount</span>
                                                    <span class="summary-discount"></span>
                                                </div>
                                                <div class='total'>
                                                    <span>Total</span>
                                                    <span class="all-total"></span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class='col-lg-8 col-md-7 col-6'>
                                    <div class="checkout">
                                    <ul class="nav nav-pills  justify-content-center align-items-center" id="pay-tab" role="tablist">
                                            @can("cash")
                                            <li class="nav-item" role="presentation">

                                                <a class="nav-link active" id="cash-tab" data-toggle="pill" href="#cash" role="tab" aria-controls="cash" aria-selected="true">

                                                    <i class="fas fa-money-bill-wave"></i>

                                                    <span>Cash</span>

                                                </a>

                                            </li>
                                            @endcan
                                            @can("credit")
                                            <li class="nav-item" role="presentation">

                                                <a class="nav-link" id="credit-tab" data-toggle="pill" href="#credit" role="tab" aria-controls="credit" aria-selected="false">

                                                    <i class="far fa-credit-card"></i>

                                                    <span>Credit</span>

                                                </a>

                                            </li>
                                            @endcan
                                            @can("hospatility")
                                            <li class="nav-item" role="presentation">

                                                <a class="nav-link" id="hospitality-tab" data-toggle="pill" href="#hospitality" role="tab" aria-controls="hospitality" aria-selected="false">

                                                    <i class="fas fa-hotel"></i>

                                                    <span>Hospitality</span>

                                                </a>

                                            </li>
                                            @endcan
                                        </ul>
                                        <div class="tab-content" id="pills-tabContent">

                                            <div class="tab-pane fade show active pay-method" id="cash" role="tabpanel" aria-labelledby="cash-tab">
                                                <div class="cash-content">
                                                    <div>
                                                        <span class='text-white'>Remainder</span>
                                                        <h2 class='price summary-price' id='cash-total-price'></h2>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cash-price" class='text-white'>Cash</label>
                                                        <input type="number" min="0" class="form-control price-value" id="cash-price">
                                                    </div>
                                                    <div>
                                                        <span class='text-white'>Rest</span>
                                                        <h3 class='price-rest'>0.00</h3>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="cash-services"  class='text-white'>Services</label>
                                                    <input type="number" min="0" class="form-control input-ser" id="cash-services">
                                                </div>
                                            </div>

                                            <div class="tab-pane fade pay-method" id="credit" role="tabpanel" aria-labelledby="credit-tab">
                                                <div class="cash-content">
                                                    <div>
                                                        <span class='text-white'>Remainder</span>
                                                        <h2 class='price summary-price' id="credit-total-price"></h2>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="visa-price" class='text-white'>Visa</label>
                                                        <input type="number" min="0" class="form-control price-value" id="visa-price">
                                                    </div>
                                                    <div>
                                                        <span class='text-white'>Rest</span>
                                                        <h3 class='price-rest'>0.00</h3>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="bank-ratio"  class='text-white'>bank ratio</label>
                                                    <input type="number" min="0" class="form-control" id="bank-ratio" data-bank="" disabled>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="visa-services"  class='text-white'>Services</label>
                                                    @can("change service")
                                                        <input type="number" min="0" class="form-control input-ser" id="visa-services">
                                                    @endcan
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="hospitality" role="tabpanel" aria-labelledby="hospitality-tab">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    @can("pay check")
                    <button class="btn btn-success d-none" type="button" id="paycheck_del">Pay</button>
                    @endcan
                    @can("print check")
                    <button class="btn btn-info" type="button" id="printcheck_hold">Print</button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <!-- End Box Model For Change Menus -->

    @include('includes.menu.delivery_order')
@stop
