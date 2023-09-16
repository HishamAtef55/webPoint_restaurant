
@php
    $title = 'Check Out';
@endphp

@extends('layouts.menu')
@section('content')
@include('includes.menu.sub_header')
    <input type="hidden" id="device_id" value="">
    <!-- Start Section Check Out -->
    <section class='check-out'>
        <div class='container'>

            <!-- Start Menu Under Navbar -->
            <div class='all-menus'>
                <!-- Start Options Menu -->
                <div id="options-menu">
                    <a href='#' class="options-item" data-toggle="modal" data-target="#menus">Menus</a>
                    <a href='#' class="options-item">Copy Check</a>
                    <a href='#' class="options-item" onclick="checkDiscountConfirm();">Discount</a>
                    <a href='#' class="options-item">Move To</a>
                    <a href='#' class="options-item">Min Charge</a>
                </div>
                <!-- Start Options Menu -->

                <!-- Start Delivery Menu -->
                <div id="delivery-menu">
                    <a href='#' class="delivery-item" href="#">Delivery Order</a>
                    <a href='#' class="delivery-item" href="#">To pilot <span>12</span></a>
                    <a href='#' class="delivery-item" href="#">Pilot Account</a>
                    <a href='#' class="delivery-item" href="#">Hold</a>
                    <a href='#' class="delivery-item" href="#">Holding List <span>1</span></a>
                </div>
                <!-- Start Delivery Menu -->

                <!-- Start Takeaway Menu -->
                <div id="takeaway-menu">
                    <a href='#' class="takeaway-item" href="#">TO GO Order</a>
                    <a href='#' class="takeaway-item" href="#">Hold</a>
                    <a href='#' class="takeaway-item" href="#">Holding List <span>1</span></a>
                </div>
                <!-- Start Takeaway Menu -->

            </div>
            <!-- End Menu Under Navbar -->

            <div class='row my-5'>
                <div class="col-lg-4 col-md-5 col-6">
                    <div class='summary'>
                        <h2>Order Summary</h2>
                        <ul class="list-unstyled">
                            <li>
                                <div>
                                    <span>Food</span>
                                    <span>4</span>
                                </div>
                                <span class='total-sub'> 125 </span>
                            </li>
                            <li>
                                <div>
                                    <span>Drink</span>
                                    <span>4</span>
                                </div>
                                <span class='total-sub'> 125 </span>
                            </li>
                            <li class='last-item'>
                                <div>
                                    <span>Items</span>
                                    <span>8</span>
                                </div>
                                <div class='total'>
                                    <span>Total</span>
                                    <span>250</span>
                                </div>
                            </li>
                        </ul>
                        <button class='btn btn-block btn-danger'>Cancel</button>
                    </div>
                </div>

                <div class='col-lg-8 col-md-7 col-6'>
                    <div class="checkout">

                        <ul class="nav nav-pills w-100 justify-content-center align-items-center" id="pills-tab" role="tablist">

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

                            <div class="tab-pane fade show active" id="cash" role="tabpanel" aria-labelledby="cash-tab"></div>

                            <div class="tab-pane fade" id="credit" role="tabpanel" aria-labelledby="credit-tab">
                                <input type="number" class="form-control form-control-lg" placeholder="Type Number">
                            </div>

                            <div class="tab-pane fade" id="hospitality" role="tabpanel" aria-labelledby="hospitality-tab">
                                <input type="Password" class="form-control form-control-lg" placeholder="Type Password">
                            </div>

                            <button class="btn btn-block btn-success btn-down" type="button" id="button-addon2">Pay</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Section Check Out -->  
    {{-- @include('includes.menu.move_to')
    <script>
        $('.moveTo').height($(window).outerHeight() - ($('.navbar').outerHeight() + $('footer').outerHeight() ));

        $(window).on('resize', function() {
          $('.moveTo').height($(window).outerHeight() - ($('.navbar').outerHeight() + $('footer').outerHeight() ));
        });

      </script> --}}
@stop



