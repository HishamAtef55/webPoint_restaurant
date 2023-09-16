@php
    $title = 'TO_GO Holding';
@endphp



@extends('layouts.menu')
@section('content')

    <!-- Start Delivery -->
    <section class='delivery'>
        <div id="check_page" value="hold_togo"></div>
        <div id="operation" value="TO_GO"></div>
        <div id="new_order"  value=""></div>

        <div id="summary_hold"></div>
        <div class='container'>
            @include('includes.menu.sub_header')
            <div class="modal-body d-flex flex-column-reverse flex-wrap togo-order">
                <div class='row w-100'>
                    <div class='col mb-5 flex-wrap' id="box_content">
                        @php
                            $counter = 0;
                            $money = 0;
                        @endphp
                        @foreach($orders as $order)
                            @php
                                $total = 0;
                                $counter++;
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
                            <div class='box' box_id="{{$order->order_id}}">
                                <ul class="list-unstyled box-list">
                                    <li>
                                        <i class="fas fa-hashtag"></i>
                                        <span class="order_id"> {{$order->order_id}}</span>
                                    </li>

                                    <li>
                                        <i class="fas fa-user"></i>
                                        @if($order->customer_name == null)
                                            <span>Customer</span>

                                        @else
                                            <span>{{$order->customer_name}}</span>

                                        @endif
                                    </li>

                                    <li>
                                        <i class="fas fa-user-tie"></i>
                                        <span>{{$order->user}}</span>
                                    </li>

                                    <li>
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>{{$order->date_holde_list}}</span>
                                    </li>

                                    <li>
                                        <i class="fas fa-stopwatch"></i>
                                        <span>{{$order->time_hold_list}}</span>
                                    </li>
                                    @php
                                        $tax = 0;
                                        $service = 0;
                                        if(!empty($alltaxandservice[$order->order_id]))
                                        {
                                            $tax     = $alltaxandservice[$order->order_id]['tax'];
                                            $service = $alltaxandservice[$order->order_id]['service'];
                                        }
                                        $money = $money + $total + $tax + $service - $dis_val;
                                    @endphp
                                    <li class='orderPrice'>
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span>{{$total + $tax + $service - $dis_val}}</span>
                                    </li>

                                </ul>
                                <div class='box-menu'>

                                    <ul>
                                        @can("take togo hold")
                                        <li class='pilot' id="take_order_hold">
                                            <a href="#">
                                                <i class="fas fa-biking fa-fw"></i>
                                                <span> Take Order </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can("edite togo hold")
                                        <li>
                                            <a href="{{url('menu/Edit_Order/'. $order->order_id)}}">
                                                <i class="fas fa-edit fa-fw"></i>
                                                <span> Edit <span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can("remove togo hold")
                                        <li id="Remove_Delivery" class='remove'>
                                            @csrf
                                            <a href="#">
                                                <i class="fas fa-trash-alt fa-fw"></i>
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
                <div class="row w-100 justify-content-center">
                    <div class="delivery-info d-flex justify-content-round">
                        <div class="mx-5">
                            <span>اجمالى الطلبات</span>
                            <span class="info-num ordersNum">{{ $counter }}</span>
                        </div>
                        <div class="mx-5">
                            <span>اجمالى المبلغ</span>
                            <span class="info-num ordersPrice">{{ $money }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Delivery -->

    <!-- End Box Model For Change Menus -->
    <div class="modal fade pay-modal" id="pay-model" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">PAY</h5>
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

                                            <li class="nav-item" role="presentation">

                                                <a class="nav-link active" id="cash-tab" data-toggle="pill" href="#cash" role="tab" aria-controls="cash" aria-selected="true">

                                                    <i class="fas fa-money-bill-wave"></i>

                                                    <span>Cash</span>

                                                </a>

                                            </li>

                                            <li class="nav-item" role="presentation">

                                                <a class="nav-link" id="credit-tab" data-toggle="pill" href="#credit" role="tab" aria-controls="credit" aria-selected="false">

                                                    <i class="far fa-credit-card"></i>

                                                    <span>Credit</span>

                                                </a>

                                            </li>

                                            <li class="nav-item" role="presentation">

                                                <a class="nav-link" id="hospitality-tab" data-toggle="pill" href="#hospitality" role="tab" aria-controls="hospitality" aria-selected="false">

                                                    <i class="fas fa-hotel"></i>

                                                    <span>Hospitality</span>

                                                </a>

                                            </li>
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
                                                    <input type="number" min="0" class="form-control input-ser" id="visa-services">
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
                    <button class="btn btn-info" type="button" id="printcheck_hold">Print</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Box Model For Change Menus -->
    @include('includes.menu.delivery_order')
@stop
