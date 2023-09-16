@php
$title = 'ToGO';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section>
    <div class='container'>
        <div class='row'>
            <div class='col-xl-8 offset-xl-2 col-lg-10 offset-lg-1'>
                <h2 class="section-title"> To-Go </h2>
                <form id="form_save_togo" action=" " method="POST" multiple enctype="multipart/form-data">
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
                        <div>
                            <div class="form-group">
                                <input type="radio" value="1" id="With_tax_service" name="discount_tax_service">
                                <label class="ml-1" for="With_tax_service">Without Discount</label>
                            </div>

                            <div class="form-group">
                                <input type="radio" value="0" id="Without_tax_service" name="discount_tax_service">
                                <label class="ml-1" for="Without_tax_service">With Discount</label>
                            </div>
                        </div>

                        <div class='form-element'>
                            <label class='input-label' for="invoice-copies">Invoice Copies No.</label>
                            <input type="number" class="mycustom-input" id="invoice-copies" name="invoice_copies" />
                            <span class='under_line'></span>
                        </div>

                        <div class='form-element'>
                            <label class='input-label' for="service-ratio">Service Ratio</label>
                            <input type="number" class="mycustom-input" name="service_ratio" id="service-ratio" />
                            <span class='under_line'></span>
                        </div>

                        <div class="select-box">
                            <select class="select_printers" id="select">
                                <option></option>
                                @foreach($printers as $printer)
                                    <option value="{{ $printer->printer }}">{{ $printer->printer }}</option>
                                @endforeach
                            </select>
                            <div class='search-select'>
                                <div class='label-Select'>
                                    <label for='printers-input'>Print Invoice...</label>
                                    <input autocomplete="off" name="printer" type='text' class="search-input"
                                        id='printers-input' />
                                    <i class='arrow'></i>
                                    <span class='line'></span>
                                </div>

                                <div class='input-options'>
                                    <ul></ul>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="slip" name="print_slip">
                            <label class="ml-1" for="slip">Print Slip</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="takeaway-receipt" name="print_togo">
                            <label class="ml-1" for="takeaway-receipt">Print To Go Receipt</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="cust-checkout-screen" name="display_checkout_screen">
                            <label class="ml-1" for="cust-checkout-screen">Display Check Out Screen</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="reservation-receipt" name="print_reservation_receipt">
                            <label class=" ml-1" for="reservation-receipt">Print Reservation Receipt</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="print-invoice" name="print_invice">
                            <label class="ml-1" for="print-invoice">Print Invoice</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="fast-check" name="fast_check">
                            <label class=" ml-1" for="fast-check">Fast Checkout</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="takeaway-to-table" name="convert_togo_table">
                            <label class=" ml-1" for="takeaway-to-table">Convert To Go To Table</label>
                        </div>

                    </div>

                    <div class='col-md-6 offset-md-3 mb-5 pb-3'>
                        <button type="submit" id="save_togo" class="btn btn-block btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@include('includes.control.Togo')
@stop
