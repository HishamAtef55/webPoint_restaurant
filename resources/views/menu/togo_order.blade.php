@php
    $title = 'TO-GO';
@endphp

@extends('layouts.menu')
@section('content')
    <!-- Start Delivery -->
    <section class='delivery'>
        <div id="check_page" value="to_pilot"></div>
        <div id="operation" value="TO_GO"></div>
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
                            $counter ++;
                            $money = $money +=$order->total;
                        @endphp
                            @if($order->total != null)
                                <div class='box' box_id="{{$order->order_id}}" box_serial="{{$order->serial_shift}}">
                            @else
                                <div class='box' box_id="{{$order->order_id}}" box_serial="{{$order->serial_shift}}" style="background:var(--gray-dark)">
                            @endif
                            <ul class="list-unstyled box-list">

                                <li>
                                    <i class="fas fa-hashtag"></i>
                                    <span class="order_id">{{$order->order_id}}</span>
                                </li>

                                <li>
                                    <i class="fas fa-fire"></i>
                                    <span class="serial_id">{{$order->serial_shift}}</span>
                                </li>

                                <li>
                                    <i class="far fa-clock"></i>
                                    <span>{{$order->t_order}}</span>
                                </li>

                                <li>
                                    <i class="fas fa-user-tie"></i>
                                    <span>{{$order->user}}</span>
                                </li>

                                <li class="orderPrice">
                                     <i class="fas fa-money-bill-wave "></i>
                                     <span>{{$order->total}}</span>
                                </li>

                            </ul>

                            <div class='box-menu'>

                                <ul>
                                    @csrf
                                    @can("edite to-go")
                                    <li>
                                        <a href="{{url('menu/Edit_Order/' . $order->order_id)}}">
                                            <i class="fas fa-edit fa-fw"></i>
                                            <span> Edit </span>
                                        </a>
                                    </li>
                                    @endcan
                                    @can("remove to-go")
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
                <div class="row w-100 justify-content-center">
                    <div class="delivery-info d-flex justify-content-around">
                        <div class="col text-center">
                            <span>اجمالى الطلبات</span>
                            <span class="ordersNum info-num">{{ $counter }}</span>
                        </div>
                        @can('total to-go')
                        <div class="col text-center">
                            <span>اجمالى المبلغ</span>
                            <span class="ordersPrice info-num">{{ $money }}</span>
                        </div>
                        @endcan
                        <div class="d-flex align-items-center">
                            <input type="text" class='col form-control use-keyboard-input' placeholder="Search For Order...">
                            <button class="btn btn-warning" id='search_order'><i  class="fas fa-search fa-fw"></i></button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- End Delivery -->

    @include('includes.menu.delivery_order')
@stop
