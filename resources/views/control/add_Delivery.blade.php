@php
$title = 'Add Delivery';
@endphp

@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section>
    <div class='container'>
        <div class='row m-0'>
            <div class='col-xl-8 offset-xl-2 col-lg-10 offset-lg-1'>
                <h2 class="section-title"> Delivery </h2>
                <form id="form_save_delivery" action=" " method="POST" multiple enctype="multipart/form-data">
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
                                <label for='branch-input'>Choose Branch...</label>
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
                            <input type="number" name="tax" class="mycustom-input" id="tax" />
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
                            <label class='input-label' for="ser-ratio">Service Ratio</label>
                            <input type="number"  name="ser_ratio" class="mycustom-input" id="ser-ratio" />
                            <span class='under_line'></span>
                        </div>

                        <div>
                            <div class="form-group">
                                <input type="checkbox" name="type_ser" value="location" id="ser-by-location">
                                <label class='ml-1' for="ser-by-location">Service by Loction</label>
                            </div>

                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" name="print_slip" id="pr-slip">
                            <label class='ml-1' for="pr-slip">Print Slip</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" name="user_slip" id="de-slip">
                            <label class='ml-1' for="de-slip">User Delivery Slip</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" name="print_pilot_slip" id="pr-invoice">
                            <label class='ml-1' for="pr-invoice">Print to pilot invoice</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" name="print_invoice" id="print-invoice">
                            <label class='ml-1' for="print-invoice">Print Invoice</label>
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

                        <div class='form-element'>
                            <label class='input-label' for="fo-cop-no">To pilot copies</label>
                            <input type="number" name="pilot_copies" class="mycustom-input" id="fo-cop-no" />
                            <span class='under_line'></span>
                        </div>

                        <div class='form-element'>
                            <label class='input-label' for="invoic-copies">Pay Copies No.</label>
                            <input type="number" name="Pay_copies" class="mycustom-input" id="invoic-copies" />
                            <span class='under_line'></span>
                        </div>

                    </div>

                    <div class='col-md-6 offset-md-3 mb-5 pb-3'>
                        <button type="submit" id="save_delivery" class="btn btn-block btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@include('includes.control.delivery')
@stop
