
@php
    $title = 'Copy Check';
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
                                <div class="d-flex flex-row mt-3">
                                    @csrf
                                    <label>Order No</label>
                                    <input type="text" id='order_num' class='form-control use-keyboard-input'>
                                </div>
                                <div class="d-flex flex-row mt-3">
                                    <label>Serial No</label>
                                    <input type="text" id='serial' class='form-control use-keyboard-input'>
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
                                    <span>Invoice No</span>
                                    <span id="order" method='order_id'></span>
                                </li>
                                <li>
                                    <span>Table No</span>
                                    <span id="order" method='table'></span>
                                </li>
                                <li>
                                    <span>Order Date</span>
                                    <span id="date" method="d_order"></span>
                                </li>
                                <li>
                                    <span>Shift</span>
                                    <span id="shift"></span>
                                </li>
                                <li>
                                    <span>Waiter</span>
                                    <span id="waiter" method="user"></span>
                                </li>
                                <li>
                                    <span>Cashier</span>
                                    <span id="cashier"></span>
                                </li>
                            </ul>

                            <span class='line'></span>

                            <ul class="table-list list-unstyled view" id='new_order_view'>

                            </ul>

                            <span class='line'></span>

                            <ul class='list-unstyled footerCopy custom-list'>
                                <li>
                                    <span>Sub Total</span>
                                    <span id="sub_total"></span>
                                </li>
                                <li>
                                    <span>Services</span>
                                    <span id="service"></span>
                                </li>
                                <li>
                                    <span>Tax</span>
                                    <span id="tax"></span>
                                </li>
                                <li>
                                    <span>Min Charge</span>
                                    <span id="mincharge" method="min_charge"></span>
                                </li>
                                <li>
                                    <span>Discount</span>
                                    <span id="discount"></span>
                                </li>
                                <li>
                                    <span>Total</span>
                                    <span id="total"></span>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Copy Check Section -->
@include('includes.menu.copycheck')
@stop



