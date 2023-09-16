@php
    $title = 'To Pilot';
@endphp

@extends('layouts.menu')
@section('content')
    <!-- Start Delivery -->
    <section class='delivery'>
        <div id="check_page" value="to_pilot"></div>
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
                            @csrf
                            <div class="input-group">
                                <select  class="custom-select">
                                    <option selected>Choose Pilot</option>
                                    @foreach($pilots as $pilot)
                                        <option value="{{$pilot->id}}">{{$pilot->name}}</option>
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
            <div class="modal-body">
                <form>
                    <div class="col-md-6 offset-md-3">
                        <div class="input-group mb-3">
                            <select id="select_location" class="custom-select">
                                <option value="all" >All Location</option>
                                @foreach($locations as $location)
                                    <option value="{{$location['id']}}" >{{$location['location']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class='row'>
                <div class='col my-5 flex-wrap' id='box_content'>
                    @foreach($orders as $order)
                        @php
                            $total = 0;
                        @endphp
                        @foreach ($order->WaitOrders as $de_order)
                            @php
                                $total = $total + $de_order->total + $de_order->total_extra + $de_order->price_details - $de_order->total_discount;
                            @endphp
                        @endforeach
                        @php
                            $cal_ratio = 0;
                            $dis_val = 0;
                            if($order['discount_type'] == 'Ratio')
                                {
                                    $cal_ratio = ($order->discount / 100) * $total;
                                    $dis_val =$cal_ratio;
                                }
                            elseif($order['discount_type'] == 'Value')
                            {
                              $dis_val = $order->discount;
                            }
                        @endphp
                        <div box_id="{{$order->order_id}}" class='box'>

                            <ul class="list-unstyled box-list">
                                <li>
                                    <i class="fas fa-hashtag"></i>
                                    <span class="order_id"> {{$order->order_id}}</span>
                                </li>

                                <li>
                                    <i class="fas fa-user"></i>
                                    <span>{{$order->customer_name}}</span>
                                </li>

                                <li>
                                    <i class="fas fa-user-tie"></i>
                                    <span>{{$order->user}}</span>
                                </li>

                                <li>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{$order->Locations->location}}</span>
                                </li>
                                @php
                                    $tax = 0;
                                    $service = 0;
                                    if(!empty($alltaxandservice[$order->order_id]))
                                    {
                                        $tax     = $alltaxandservice[$order->order_id]['tax'];
                                        $service = $alltaxandservice[$order->order_id]['service'];
                                    }
                                @endphp
                                <li>
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>{{$total + $tax + $service - $dis_val}}</span>
                                </li>

                            </ul>

                            <div class='box-menu'>

                                <ul>
                                    @csrf
                                    @can("add pilot")
                                    <li data-toggle="modal" data-target="#pilot">
                                        <a href="#">
                                            <i class="fas fa-biking fa-fw"></i>
                                            <span>Pilot</span>
                                        </a>
                                    </li>
                                    @endcan
                                    @can("edite delivery")
                                    <li>
                                        <a href="{{url('menu/Edit_Order/'. $order->order_id)}}">
                                            <i class="fas fa-edit fa-fw"></i>
                                            <span>Edit</span>
                                        </a>
                                    </li>
                                    @endcan
                                    @can("remove delivery")
                                    <li id="Remove_Delivery" class='remove'>
                                        @csrf
                                        <a href="#">
                                            <i  class="fas fa-trash-alt fa-fw"></i>
                                            <span>Remove</span>
                                        </a>
                                    </li>
                                    @endcan

                                </ul>

                            </div>


                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </section>
    <!-- End Delivery -->

    <!-- End Box Model For Change Menus -->
    <div class="modal fade pay-modal" id="pay-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                            </div>
                        </div>
                    </section>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    @can('print check')
                    <button class="btn btn-info" type="button" id="printcheck_hold">Print</button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <!-- End Box Model For Change Menus -->

    @include('includes.menu.delivery_order')
@stop
