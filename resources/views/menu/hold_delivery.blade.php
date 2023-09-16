@php
    $title = 'Delivery Holding';
@endphp

@extends('layouts.menu')
@section('content')
    <!-- Start Delivery -->
    <section class='delivery'>
        <div id="check_page" value="hold_order"></div>
        <div id="new_order"  value=""></div>
        <div id="operation" value="Delivery"></div>
      <div class='container'>
        @include('includes.menu.sub_header')
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
                            @endphp
                            <li>
                                <i class="fas fa-money-bill-wave"></i>
                                <span>{{$total + $tax + $service - $dis_val}}</span>
                            </li>

                        </ul>

                        <div class='box-menu'>

                            <ul>
                                @can("take delivery hold")
                                <li class='pilot'  id="take_order_hold">
                                    <a href="#">
                                        <i class="fas fa-biking fa-fw"></i>
                                        <span> Take Order </span>
                                    </a>
                                </li>
                                @endcan
                                @can("edite delivery hold")
                                <li>
                                    <a href="{{url('menu/Edit_Order/' . $order->order_id)}}">
                                        <i class="fas fa-edit fa-fw"></i>
                                        <span> Edit </span>
                                    </a>
                                </li>
                                @endcan
                                @can("remove delivery hold")
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
      </div>
    </section>
    <!-- End Delivery -->

    @include('includes.menu.delivery_order')
@stop
