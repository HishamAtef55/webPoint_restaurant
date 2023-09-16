
@php
    $title = 'Copy Close Shift';
@endphp

@extends('layouts.menu')
@section('content')
@include('includes.menu.sub_header')
    <input type="hidden" id="device_id" value="">
        <!-- Start Copy Check Section -->
        <section class='copyCheck'>
            <div class='container'>
                <div class='row my-5 flex-row-reverse'>
                    <div class='col-lg-6'>
                        <div class='col'>
                            <div class='d-flex flex-column mb-3 '>
                                <div class="select-container">
                                    <input type="date" class="form-control select-report" id="date" value="<?php echo date('Y-m-d',strtotime("0 days"));?>"  max="<?php echo date('Y-m-d',strtotime("0 days"));?>" dataformatas="dd/mm/yyyy">
                                </div>
                                <div class="d-flex flex-row mt-3">
                                    @csrf
                                    <select  class="new_table custom-select"  name="new_table" id="shift">
                                        <option selected disabled>Choose Shift...</option>
                                        @foreach($shifts as $shift)
                                            <option value="{{$shift->id}}">{{$shift->shift}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class='col'>
                            <div class='d-flex flex-column mt-3'>
                                <button class='btn btn-block text-white bg-info mb-2' id='view_check'>View</button>
                                <button class='btn btn-block text-white bg-success' id='copy_check'>Copy</button>
                            </div>
                        </div>
                    </div>

                    <div class='col-lg-6'>
                        <div class='checkCopy'>

                            <ul class='list-unstyled aboutCopy custom-list'>
                                <li>
                                    <span>Min Order No</span>
                                    <span id="min_order_no"></span>
                                </li>
                                <li>
                                    <span>Max Order No</span>
                                    <span id="max_order_no" method='max_order_no'></span>
                                </li>
                                <li>
                                    <span>Guests No</span>
                                    <span id="guests_no" method="guests_no"></span>
                                </li>
                                <li>
                                    <span>Guests AVG</span>
                                    <span id="guests_avg" method="guests_avg"></span>
                                </li>

                                <span class='line'></span>

                                <li>
                                    <span>Cash</span>
                                    <span id="cash" ></span>
                                </li>
                                <li>
                                    <span>Visa</span>
                                    <span id="visa"></span>
                                </li>
                                <li>
                                    <span>Hospitality</span>
                                    <span id="hos"></span>
                                </li>
                                <span class='line'></span>
                                <li>
                                    <span>Total Cash</span>
                                    <span id="total_cash"></span>
                                </li>
                                <li>
                                    <span>Customer Payment</span>
                                    <span id="cus_payment"></span>
                                </li>
                                <li>
                                    <span>Total</span>
                                    <span id="total"></span>
                                </li>
                                <span class='line'></span>
                            </ul>
                            <ul class='list-unstyled footerCopy custom-list' id="sales_group">

                            </ul>
                            <ul class='list-unstyled footerCopy custom-list'>
                                <span class='line'></span>

                                <li>
                                    <span>Table Services</span>
                                    <span id="t_services"></span>
                                </li>
                                <li>
                                    <span>Delivery Services</span>
                                    <span id="de_services"></span>
                                </li>
                                <li>
                                    <span>TOGO Services</span>
                                    <span id="to_Services"></span>
                                </li>
                                <span class='line'></span>
                                <li>
                                    <span>Table Tax</span>
                                    <span id="t_tax"></span>
                                </li>
                                <li>
                                    <span>Delivery Tax</span>
                                    <span id="de_tax"></span>
                                </li>
                                <li>
                                    <span>TOGO Tax</span>
                                    <span id="to_tax"></span>
                                </li>
                                <span class='line'></span>
                                <li>
                                    <span>Tables Orders No</span>
                                    <span id="t_orders_no"></span>
                                </li>
                                <li>
                                    <span>Delivery Orders No</span>
                                    <span id="de_orders_no"></span>
                                </li>
                                <li>
                                    <span>TOGO Orders No</span>
                                    <span id="to_orders_no"></span>
                                </li>
                                <span class='line'></span>
                                <li>
                                    <span>Details</span>
                                    <span id="details"></span>
                                </li>
                                <li>
                                    <span>Extras</span>
                                    <span id="extras"></span>
                                </li>
                                <li>
                                    <span>Discounts</span>
                                    <span id="discounts"></span>
                                </li>
                                <li>
                                    <span>Service</span>
                                    <span id="services"></span>
                                </li>
                                <li>
                                    <span>Tax</span>
                                    <span id="tax"></span>
                                </li>
                                <li>
                                    <span>Tip</span>
                                    <span id="tip"></span>
                                </li>
                                <li>
                                    <span>Ratio Bank</span>
                                    <span id="ration_bank"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Copy Check Section -->
@include('includes.menu.copy_close_shift')
@stop



