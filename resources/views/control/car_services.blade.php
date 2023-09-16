@php
$title = 'Car-Services';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section>
    <section>
        <div class='container'>
            <div class='row'>

                <div class='col-xl-8 offset-xl-2 col-lg-10 offset-lg-1'>
                    <h2 class='section-title'> Car Services </h2>
                    <form id="form_save_car_services" action=" " method="POST" multiple enctype="multipart/form-data">
                        @csrf

                        <div class="select-box">
                            <select class="select_Branch" name="branch" id="select_branch">
                                <option value=""></option>
                                @foreach($branchs as $branch)
                                <option value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>

                            <div class='search-select'>
                                <div class='label-Select'>
                                    <label for='branch-input'>Chose Branch...</label>
                                    <input autocomplete="off" type='text' class="search-input" id='branch-input' />
                                    <i class='arrow'></i>
                                    <span class='line'></span>
                                </div>

                                <div class='input-options'>
                                    <ul></ul>
                                </div>

                            </div>

                        </div>

                        <div class='custom-grid-delivery'>
                            
                            <div class='form-element'>
                                <label class='input-label' for="tax">Tax</label>
                                <input type="number" class="mycustom-input" id="tax" name="tax" />
                                <span class='under_line'></span>
                            </div>

                            <div class='form-element'>
                                <label class='input-label' for="invoice-copies">Invoice Copies No.</label>
                                <input type="number" class="mycustom-input" id="invoice_copies" name="invoice_copies" />
                                <span class='under_line'></span>
                            </div>

                            <div class='form-element'>
                                <label class='input-label' for="service-ratio">Service Ratio</label>
                                <input type="number" class="mycustom-input" id="service_ratio" name="service_ratio" />
                                <span class='under_line'></span>
                            </div>

                            <div class="select-box">
                                <select class="select_printers" name="printers" id="printers">
                                    <option value="One">One</option>
                                    <option value="Two">Two</option>
                                    <option value="Three">Three</option>
                                    <option value="Four">Four</option>
                                </select>

                                <div class='search-select'>
                                    <div class='label-Select'>
                                        <label for='printers_input'>Print Invoice...</label>
                                        <input autocomplete="off" type='text' class="search-input"
                                            id='printers_input' name="printers_input" />
                                        <i class='arrow'></i>
                                        <span class='line'></span>
                                    </div>

                                    <div class='input-options'>
                                        <ul></ul>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="checkbox" value="1"id="slip" name="slip">
                                <label class="ml-1" for="slip">Print Slip</label>
                            </div>

                            <div class="form-group">
                                <input type="checkbox" value="1" id="car_service_receipt" name="car_service_receipt">
                                <label class="ml-1" for="car-service-receipt">Print Car Service Receipt</label>
                            </div>

                            <div class="form-group">
                                <input type="checkbox" value="1" id="reservation_receipt" name="reservation_receipt">
                                <label class="ml-1" for="reservation-receipt">Print Reservation  Receipt</label>
                            </div>

                            <div class="form-group">
                                <input type="checkbox" value="1" id="print_invoice" name="print_invoice">
                                <label class="ml-1" for="print-invoice">Print Invoice</label>
                            </div>

                            <div class="form-group">
                                <input type="checkbox" value="1" id="fast_check" name="fast_check">
                                <label class="ml-1" for="fast-check">Fast Checkout</label>
                            </div>
                        </div>

                        <div class='col-md-6 offset-md-3 mb-5 pb-3'>
                            <button id="save_car_Services" type="submit" class="btn btn-block btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @include('includes.control.car_services')
    @stop
