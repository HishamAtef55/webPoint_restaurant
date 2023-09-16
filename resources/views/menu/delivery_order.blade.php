@php
    $title = 'Delivery OrdersM ';
@endphp

@extends('layouts.menu')
@section('content')
    <!-- Start Delivery -->
    <section class='delivery'>
        <div id="check_page" value="delivery_order"></div>
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
                        @endphp
                        @foreach($orders as $order)
                        @php
                            $counter ++;
                            $money = $money + $order->total;
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

                                    <li>
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span>{{$order->total}}</span>
                                    </li>

                                </ul>

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
                                    <span>اجمالى الطلبات</span>
                                    <span class="info-num">{{$counter}}</span>
                                </div>
                                <div>
                                    <span>اجمالى المبلغ</span>
                                    <span class="info-num">{{$money}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </section>
    <!-- End Delivery -->

    @include('includes.menu.delivery_order')
@stop
