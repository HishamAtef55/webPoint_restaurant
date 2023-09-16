@php
    $title = 'Add Order  ';
@endphp

@extends('layouts.menu')
@section('content')
    <style>
        @media (max-width: 991.98px) {
            .claculator-btn,
            .check-btn {
                display: block;
            }
        }
    </style>
    <input type="hidden" id="device_id" value="">

    @if(isset($delivery))
        <div id="delivery_val"  value="{{$delivery}}"></div>
    @else
        <div id="delivery_val"  value="0"></div>
    @endif
    @if(isset($state_cus))
        <div id="Edit_customer"  value="{{$state_cus}}"></div>
    @else
        <div id="Edit_customer"  value="New_customer"></div>
    @endif
    @if(isset($min_charge))
    <div id="min_charge_menu"  value="{{$min_charge}}"></div>
    @endif
    @if(isset($table))
    <div id="table_id"  value="{{$table}}"></div>
    @else
        <div id="table_id"  value=""></div>
        <div id="togo_table"  value=""></div>
     @endif
     @if(isset($new_order))
     <div id="new_order"  value="{{$new_order}}"></div>
     @else
     <div id="new_order"  value=""></div>
     @endif

    <div id="operation" value="{{$operation}}"></div>

    @if(isset($check_hold))
        <div id="check_hold" value="{{$check_hold}}"></div>
    @endif
    @can('discount')
        <div id="checkPerDiscount" value="discount"></div>
    @elsecan('discount')
        <div id="checkPerDiscount" value="no"></div>
    @endcan
    <div class="modal fade create_item" id="create_item" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label> Item Name </label>
                        <input name="item_name" type="text" class="form-control use-keyboard-input" id='itemName' autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label> Price </label>
                        <input name="item_price" type="number" class="form-control use-keyboard-input" id='itemPrice' autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label> Qty </label>
                        <input name="qty_time" type="number" class="form-control use-keyboard-input" id='itemQty' autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label> Chose Printer </label>
                        <select class="custom-select" id="chose_printer_item" multiple>
                            @foreach($printers as $printer)
                                <option value="{{$printer->printer}}">{{$printer->printer}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="add_item">Save Item</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="set_table_number" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Set Table Number</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label> Table Number  </label>
                        <input name="table_num" type="number" class="form-control use-keyboard-input" id='tableNum' autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="set_table">Save</button>
                </div>
            </div>
        </div>
    </div>
    @if($operation == 'Delivery' || $operation == 'TO_GO')

        <div class="modal fade add-location" id="addLocationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label> Location Name </label>
                        <input name="location_name" type="text" class="form-control use-keyboard-input" id='locationName'>
                    </div>
                    <div class="mb-3">
                        <label> Price </label>
                        <input name="location_price" type="number" class="form-control use-keyboard-input" id='locationPrice'>
                    </div>
                    <div class="mb-3">
                        <label> Time </label>
                        <input name="location_time" type="number" class="form-control use-keyboard-input" id='locationTime'>
                    </div>
                    <div class="mb-3">
                        <label> Pilot Value</label>
                        <input name="pilotValue" value="0" type="number" class="form-control use-keyboard-input" id='pilotValue'>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="add_location">Save Location</button>
                </div>
                </div>
            </div>
        </div>

        <!-- Start Box Model For Customer -->
        <div class="modal fade customer" id="Customer-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form_save_customer" action=" " method="POST" multiple enctype="multipart/form-data">
                            @csrf
                            <div class='row my-4'>
                                <div class='col-md-7'>
                                    <input type="hidden" name="id" value="" id="row_id"/>
                                    <input type="hidden" name="id_phone" value="" id="id_phone"/>
                                    <input type="hidden" name='device' id="device" value="">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label> Name </label>
                                            <input type="text" name="name" class="form-control use-keyboard-input" value="" id='cus_name'>
                                        </div>
                                        <div class="col-md-6 mb-3 phone">
                                            <label> Phone </label>
                                            <input type="text" name="phone[]" class="form-control use-keyboard-input" value="" id='cus_phone'>
                                            <span class="plus"> <i class="fas fa-plus"></i> </span>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label> Location </label>
                                            <select class="main_table custom-select" name="location" id="cus_location">
                                                @foreach($locations as $location)
                                                <option price="{{$location->price}}" time="{{$location->time}}" value="{{$location->id}}">{{$location->location}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label> Street </label>
                                            <input type="text" name="street" class="form-control use-keyboard-input" value="" id='cus_street'>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label> Address </label>
                                            <input name="address" type="text" class="form-control use-keyboard-input" value="" id='cus_address'>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label> Special Marque </label>
                                            <input name="special_marque" type="text" class="form-control use-keyboard-input" value="" id="special_marque">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label> Role </label>
                                            <input id='cus_role' name="role" type="text" class="form-control use-keyboard-input" value="" >
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label> Dpartment </label>
                                            <input id='cus_department' name="department" type="text" class="form-control use-keyboard-input" value="" >
                                        </div>
                                    </div>
                                    <input type="hidden" name="branch" id="branch" value="{{Auth::user()->branch_id}}">
                                    <div class="form-row">
                                        <div class="col mb-3">
                                            <label> Notes </label>
                                            <textarea name="notes" class="form-control use-keyboard-input" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class='buttons d-flex'>
                                        <button id="save_customer" class='btn btn-success'> Save </button>
                                        <button id="update_customer" class='btn btn-primary mx-4 closed'> Update </button>
                                        <button id="order_customer" class='btn btn-info closed' > Order </button>
                                        <button class='btn btn-warning mx-3' data-toggle="modal" data-target="#addLocationModal" onclick="event.preventDefault()"> Add Location </button>
                                    </div>
                                </div>
                                <div class='col-md-5'>
                                    <div class="form-row">
                                        <div class="col-4 mb-3">
                                            <input name="search_" op="search_phone" class="test_rad" type="radio"  value="search_phone" id="search_phone" checked>
                                            <label for="search_phone">  Phone </label>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <input name="search_" id="search_name" op="search_name" class="test_rad" type="radio"  value="search_name" >
                                            <label for="search_name"> Name </label>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <input name="search_" id="search_location" op="search_location" class="test_rad" type="radio"  value="search_location" >
                                            <label for="search_location">Location </label>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col mb-3">
                                            <label> Search </label>
                                            <input name="search" op="search_location" type="text" class="form-control  search" value="" >
                                        </div>
                                    </div>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Location Name</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Box Model For Customer -->

        <div class="modal fade" id="hold-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hold</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-5 pb-5">
                        @if(isset($customer_order))
                        <form>
                            <div class="form-group">
                                <label for="date-input">Date</label>
                                <input value="{{$customer_order[0]->date_holde_list}}" type="date" class="form-control" id="date-input-hold" dataformatas="dd/mm/yyyy" name="date" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="form-group">
                                    <label for="time-input">Time</label>
                                    <input value="{{$customer_order[0]->time_hold_list}}" type="time" class="form-control" id="time-input-hold" name="time_from" value="<?php echo date('H:i'); ?>" required>
                            </div>
                        </form>
                        @else
                            <form>
                                <div class="form-group">
                                    <label for="date-input">Date</label>
                                    <input type="date" class="form-control" id="date-input-hold" dataformatas="dd/mm/yyyy" name="date" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="time-input">Time</label>
                                    <input type="time" class="form-control" id="time-input-hold" name="time_from" value="<?php echo date('H:i'); ?>" required>
                                </div>
                            </form>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" id="save_hold_delivery" class="btn btn-success">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <div class='container'>
    @endif
    <!-- Start Box Model For Item Menu -->
    <div class="modal fade item-menu-model" id="item-menu-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link" id="nav-comment-tab" data-toggle="tab" href="#nav-comment" role="tab" aria-controls="nav-home" aria-selected="true">Comment</a>
                        <a class="nav-item nav-link" id="nav-extra-tab" data-toggle="tab" href="#nav-extra" role="tab" aria-controls="nav-profile" aria-selected="false">Extra</a>
                        <a class="nav-item nav-link" id="nav-without-tab" data-toggle="tab" href="#nav-without" role="tab" aria-controls="nav-contact" aria-selected="false">Without</a>
                        <a class="nav-item nav-link" id="nav-discount-tab" data-toggle="tab" href="#nav-discount" role="tab" aria-controls="nav-contact" aria-selected="false">Discount</a>
                    </div>
                </nav>
                <div class="tab-content p-3" id="nav-tabContent">
                    <div class="input-group mb-3">
                        <select class="custom-select" id='num_quant'>
                        </select>
                    </div>
                    <div class="tab-pane fade " id="nav-comment" role="tabpanel" aria-labelledby="nav-comment-tab">
                        <textarea id="text_area_comment" class="form-control mb-2"  rows="6" placeholder="Type Comment"></textarea>
                        <button id="save_comment" number_order="" class='btn btn-block bg-success text-white'>Save</button>
                    </div>
                    <div class="tab-pane fade" id="nav-extra" role="tabpanel" aria-labelledby="nav-extra-tab">
                        <div id='extra-container' class='d-flex align-items-center flex-wrap'></div>
                    </div>
                    <div class="tab-pane fade" id="nav-without" role="tabpanel" aria-labelledby="nav-without-tab">
                        <textarea class="form-control mb-2" id="text_area_without" rows="6" placeholder="Type Without"></textarea>
                        <button id="save_without" number_order="" class='btn btn-block bg-success text-white'>Save</button>
                    </div>
                    <div class="tab-pane fade" id="nav-discount" role="tabpanel" aria-labelledby="nav-discount-tab">
                        <div class="radios mb-5">
                            <div class="radios-header p-2  d-block text-white">Discount Type</div>

                            <div class="radio-box p-3 shadow">
                                <div class="form-check">
                                    <input class="form-check-input" onchange="handelRadio(this.dataset.value)" type="radio" name="type_discount" id="Ratio" data-value="#ratio" checked>
                                    <label class="form-check-label" for="Ratio">Discount Ratio</label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" onchange="handelRadio(this.dataset.value)" type="radio" name="type_discount" id="Value" data-value="#value">
                                    <label class="form-check-label" for="Value">Discount Value</label>
                                </div>
                            </div>

                        </div>

                        <div class='discount-content'>
                            <div id="ratio">
                                <div class="input-group mb-3">
                                    <select class="custom-select" id='ratio-select'>
                                        <option selected disabled value="2">Choose Discount Ratio</option>
                                        @foreach($discount_ratio as $ratio)
                                            @can($ratio->name . '-discount')
                                        <option id_discount ="{{$ratio->id}}" type="{{$ratio->type}}" value="{{$ratio->value}}" name_dis="{{$ratio->name}}">{{$ratio->name}}</option>
                                            @endcan
                                        @endforeach
                                    </select>
                                    <input type='hidden' value='' name_dis="Discount"/>
                                </div>
                            </div>

                            <div class="d-none" id="value">
                                <div class="mb-3">
                                    <select class="custom-select" id='value-select'>
                                        <option selected disabled>Choose Discount Value</option>
                                        @foreach($discount_value as $value)
                                         @can($value->name . '-discount')
                                            <option id_discount ="{{$value->id}}" type="{{$value->type}}" value="{{$value->value}}" name_dis="{{$value->name}}">{{$value->name}}</option>
                                        @endcan
                                        @endforeach
                                    </select>
                                    @can('insert value discount')
                                    <input type='text' placeholder='Discount Value' class="form-control mt-3" name_dis="Discount" oninput="selectDisabled(this)"/>
                                    @endcan
                                </div>
                            </div>
                        </div>

                        <button id="save_discount_item" class='btn btn-block btn-success'>Save</button>

                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Box Model For Item Menu -->


    <!-- Start Box Model For Item details -->
    <div class="modal fade details-model" id="details-model" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Details Item</h5>
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
                </div>
                <div class="modal-body">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist"></div>
                    </nav>
                    <div class="tab-content p-3" id="nav-tabContent"></div>
                </div>

                <div class="modal-footer justify-content-between">
                    <p>Max : <span class='details-max'></span></p>
                    <button type="button" id="dismiss_modal" class="btn btn-danger d-none" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Box Model For Item details -->

    <!-- Start Box Model For Change Menus -->
    <div class="modal fade" id="menus" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="input-group mb-3">
                            <select id="select_change_menu" class="custom-select">
                                <option selected>Choose Menu</option>
                                @foreach ($menus as $menu)
                                    <option value="{{$menu->id}}" id="menu_{{$menu->id}}">{{$menu->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" branch_id="{{$menus[0]->branch_id}}" id="change_menu" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Box Model For Change Menus -->
    <div class="modal fade pay-modal" id="pay-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">PAY</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                                            @can("cash")
                                            <li class="nav-item" role="presentation">

                                                <a class="nav-link active" id="cash-tab" data-toggle="pill" href="#cash" role="tab" aria-controls="cash" aria-selected="true">

                                                    <i class="fas fa-money-bill-wave"></i>

                                                    <span>Cash</span>

                                                </a>

                                            </li>
                                            @endcan
                                            @can("credit")
                                            <li class="nav-item" role="presentation">

                                                <a class="nav-link" id="credit-tab" data-toggle="pill" href="#credit" role="tab" aria-controls="credit" aria-selected="false">

                                                    <i class="far fa-credit-card"></i>

                                                    <span>Credit</span>

                                                </a>

                                            </li>
                                            @endcan
                                            @can("hospatility")
                                            <li class="nav-item" role="presentation">

                                                <a class="nav-link" id="hospitality-tab" data-toggle="pill" href="#hospitality" role="tab" aria-controls="hospitality" aria-selected="false">

                                                    <i class="fas fa-hotel"></i>

                                                    <span>Hospitality</span>

                                                </a>

                                            </li>
                                            @endcan
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
                                                        <input type="number" min="0" class="form-control price-value use-keyboard-input" id="cash-price">
                                                    </div>
                                                    <div>
                                                        <span class='text-white'>Rest</span>
                                                        <h3 class='price-rest'>0.00</h3>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="cash-services"  class='text-white'>Services</label>
                                                    @can('change service')
                                                    <input type="number" min="0" class="form-control input-ser use-keyboard-input" id="cash-services">
                                                    @endcan
                                                    @cannot("change service")
                                                    <input type="number" min="0" class="form-control input-ser use-keyboard-input" id="cash-services" readonly>
                                                    @endcannot
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
                                                        <input type="number" min="0" class="form-control price-value use-keyboard-input" id="visa-price">
                                                    </div>
                                                    <div>
                                                        <span class='text-white'>Rest</span>
                                                        <h3 class='price-rest'>0.00</h3>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="bank-ratio"  class='text-white'>bank ratio</label>
                                                    <input type="number" min="0" class="form-control use-keyboard-input" id="bank-ratio" data-bank="" disabled>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="visa-services"  class='text-white'>Services</label>
                                                    @can('change service')
                                                        <input type="number" min="0" class="form-control input-ser use-keyboard-input" id="visa-services">
                                                    @endcan
                                                    @cannot("change service")
                                                        <input type="number" min="0" class="form-control input-ser use-keyboard-input" id="visa-services" readonly disabled>
                                                    @endcannot
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
                    @php $check_per = 0; @endphp
                    @if($no_print > 0)
                        @can("pay check")
                        <button class="btn btn-success" type="button" id="paycheck">Pay</button>
                        @endcan
                    @endif

                    @can("print check more than once")
                    @php $check_per = 1; @endphp
                        <button class="btn btn-info" type="button" id="printcheck">Print</button>
                    @endcan

                    @if($check_per == 0)
                    @can("print check")
                    @if($no_print < 1)
                        <button class="btn btn-info" type="button" id="printcheck">Print</button>
                    @endif
                    @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Box Model For Change Menus -->

    <!-- Start Box Model For Discount On All Check -->
    <div class="modal fade discount-all" id="discount-on-all-check" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Discount</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body d-flex pt-5 pb-5">
                    <div class='discount-list'>
                        <h4>Discount List</h4>
                        @if(isset($Orders))
                        @foreach($Orders as $order)
                            @if($order->discount > 0)
                                <div class='discount-item' disid="{{$order->sub_num_order}}">
                                    <div class='item-name'>{{$order->name}}</div>
                                    <button order_no="{{$order->order_id}}" item_id = "{{$order->sub_num_order}}" class='btn btn-danger del_discount_item' discount ="{{$order->total}}">
                                        <i class='fas fa-trash-alt text-white'></i>
                                    </button>
                                </div>
                            @endif
                        @endforeach
                        @endif
                    </div>

                    <div class='discount-type p-3'>
                        <div class="radios mb-5">
                            <h4 class="radios-header">Discount Type</h4>
                            <div class="radio-box p-3 shadow">
                                <div class="form-check">
                                    <input class="form-check-input " onchange="handelRadio(this.dataset.value)" type="radio" name="discount-type" id="dis-ratio-all" data-value="#ratio-all" dis-type="Ratio" checked>
                                    <label class="form-check-label text-white" for="dis-ratio-all">Discount Ratio</label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" onchange="handelRadio(this.dataset.value)" type="radio" name="discount-type" id="dis-value-all" data-value="#value-all" dis-type="Value">
                                    <label class="form-check-label text-white" for="dis-value-all">Discount Value</label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div id="ratio-all">
                                <div class="mb-3">
                                    <select class="custom-select">
                                      <option selected disabled value="">Choose Discount Ratio</option>
                                      @foreach($discount_ratio as $ratio)
                                          @can($ratio->name . '-discount')
                                                <option id_discount ="{{$ratio->id}}" value="{{$ratio->value}}" name_dis="{{$ratio->name}}">{{$ratio->name}}</option>
                                          @endcan
                                      @endforeach
                                    </select>
                                    @can('insert value discount')
                                    <input type='number' placeholder='Discount ratio' class="form-control mt-3 use-keyboard-input" oninput="selectDisabled(this)"/>
                                    @endcan
                                </div>
                            </div>

                            <div class="d-none" id="value-all">
                                <div class="mb-3">
                                    <select class="custom-select">
                                      <option selected disabled>Choose Discount Value</option>
                                      @foreach($discount_value as $value)
                                            @can($value->name . '-discount')
                                                <option id_discount ="{{$value->id}}" value="{{$value->value}}" name_dis="{{$value->name}}">{{$value->name}}</option>
                                            @endcan
                                      @endforeach
                                    </select>
                                    @can('insert value discount')
                                    <input type='number' placeholder='Discount Value' class="form-control mt-3 use-keyboard-input" oninput="selectDisabled(this)"/>
                                    @endcan
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="save_discount_all">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Box Model For Discount On All Check -->

    <!-- Start Min Charge Modal -->
    <div class="modal fade" id="min_charge_modal_menu" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Guests</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="guest-input">Guest No.</label>
                    <input type="number" class="form-control use-keyboard-input" id="guest-input" name="guest" min='1'>
                </div>
                <div class="form-group">
                    <label for="minchrage-input">Min-Charge</label>
                    <input type="number" class="form-control" id="minchrage-input" name="min_charge" disabled>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="history.back();">Close</button>
                <button type="button" class="btn btn-success" id="save_mincharge_menu">Save</button>
            </div>
            </div>
        </div>
    </div>
    <!-- End Min Charge Modal -->


    <!-- Start Main Container -->
    <main class="overflow-hidden">

        <div class='row flex-row-reverse'>

            <!-- Start Check Out Slide -->
            <div class='col-lg-4 col-md-6 pl-lg-1 pl-xl-3'>

                <div class='check' dis-type="{{$dis_type}}" dis-val="{{$dis}}">

                    <!-- Customer Row  -->
                    <div class='table-customer d-flex font-weight-bold'>
                        <div class='barcode'>
                            <input type='text' placeholder="Search Items"/>
                        </div>
                    </div>

                    <!-- Check Info Row  -->
                    <div class='table-info d-flex bg-dark text-white font-weight-bold'>
                        @if(isset($table))
                        <div>
                            <span>Table</span>
                            <span>{{$table}}</span>
                        </div>
                        @endif

                        @if($operation == 'TO_GO')
                        <div class="text-center" id="table_num_div">
                            <span>Table</span>
                            <button class='btn btn-info btn-sm' data-toggle="modal" data-target="#set_table_number">Set Table</button>
                            <span></span>
                        </div>
                        @endif


                        <div class='orderCheck'>
                            <span>Order</span>
                            @if(isset($new_order))
                            <span>{{$new_order}}</span>
                            @else
                            <span></span>
                            @endif
                        </div>

                        <div>
                            <span>Captain</span>
                            @if($capten == '0')
                            <span>{{Auth::user()->name}}</span>
                            @else
                            <span>{{$capten}}</span>
                            @endif
                        </div>
                        @if($customer == NUll)
                        @else
                        <div class='cusName'>
                            <span>Customer</span>
                            <span>{{$customer}}</span>

                        </div>
                        @endif

                        @if($mincharge == '0')
                        @else
                        <div class='minChargeCheck'>
                            <span>Min Charge</span>
                            <span>{{$mincharge}}</span>
                        </div>
                        <div class='guestCheck'>
                            <span>Guest</span>
                            <span>{{ $gust }}</span>
                        </div>
                        @endif
                        <div class='d-flex align-items-center justify-content-center'>
                            <button class='btn-barcode'>
                                <i class="fas fa-barcode"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Table Check  -->
                    <div class='table-check'>

                        <!-- Body Table Check  -->
                        <div class='table-body'>
                            @if(isset($Orders))
                                @foreach($Orders as $order)
                                    @if($order->status_take == '0')
                                    <div class='item-parent' value="{{$order->total + $order->total_extra + $order->price_details}}" pick_up="{{$order->pick_up}}" item="{{$order ->item_id}}" item_id="{{$order -> sub_num_order}}">
                                    @elseif($order->status_take == '1')
                                        @can("void items")
                                        <div class='item-parent' value="{{$order->total + $order->total_extra + $order->price_details}}" pick_up="{{$order->pick_up}}" item="{{$order ->item_id}}" item_id="{{$order -> sub_num_order}}">
                                        @endcan
                                        @cannot("void items")
                                        <div class='item-parent send' value="{{$order->total + $order->total_extra + $order->price_details}}" pick_up="{{$order->pick_up}}" item="{{$order ->item_id}}" item_id="{{$order -> sub_num_order}}">
                                        @endcannot
                                    @endif
                                        <div class='table-item'>
                                            <div class='item-menu'>
                                                <button class='btn' id="comment" id_order="{{$order->sub_num_order}}" data-toggle="modal" data-target="#item-menu-model" data-model="comment">    <i class='fas fa-comment-dots'></i>
                                                    Comment
                                                    <input type="hidden" value="{{$order->comment}}"  class="comment_content">
                                                </button>

{{--                                                <button class='btn'  id_order="{{$order->sub_num_order}}" data-toggle="modal" data-target="#item-menu-model" data-model="extra">--}}
{{--                                                    <i class='fas fa-plus-square'></i>--}}
{{--                                                    Extra--}}
{{--                                                </button>--}}

                                                <button class='btn' id="without" id_order="{{$order->sub_num_order}}" data-toggle="modal" data-target="#item-menu-model" data-model="without">    <i class='fas fa-minus-square'></i>
                                                    Without
                                                </button>
                                                @can('discount')
                                                <button class='btn' id_order="{{$order->sub_num_order}}" data-toggle="modal" data-target="#item-menu-model" data-model="discount">    <i class='fas fa-tags'></i>
                                                    Discount
                                                </button>
                                                @endcan
                                            </div>
                                            <div class='product-name'>
                                                <span> {{$order -> name}} </span>
                                                <span style="opacity: 0.3"> {{$order->created_at->format('H:i')}} </span>
                                            </div>
                                            <div> <input type='number' value='{{$order -> quantity}}' min='1' step="0.1" class='num' disabled/> </div>
                                            <div class='price'>{{$order -> price}}</div>
                                            <div id="total_{{$order -> sub_num_order}}" class='total'> <input type='number' value='{{(($order->total)+($order->price_details)+($order->total_extra) - ($order->total_discount))}}' min='1' disabled class='total-input' /> </div>
                                            <button class='btn btn-danger trash' id_order="{{$order->sub_num_order}}"> <i class='fas fa-trash-alt text-white'></i> </button>

                                        </div>
                                            @if($order->discount >= 1)
                                                @if($order->discount_type == "Value")
                                                    <div class='discount' id="discount_{{$order -> sub_num_order}}">
                                                        <div class='discount-name'>
                                                            <span>Discount</span>
                                                            <span>{{$order->discount_name}}</span>
                                                        </div>
                                                        <div class='discount-price' type="{{$order->discount_type}}">{{$order->discount}}</div>
                                                    </div>
                                                @elseif($order->discount_type == "Ratio")
                                                    <div class='discount' id="discount_{{$order -> sub_num_order}}">
                                                        <div class='discount-name'>
                                                            <span>Discount</span>
                                                            <span>{{$order->discount_name}}</span>
                                                        </div>
                                                        <div class='discount-price' type="{{$order->discount_type}}">{{$order->total_discount}}</div>
                                                    </div>
                                                @endif

                                            @endif
                                            @foreach($order->details as $detail)
                                                <div class='details' id="{{$detail -> detail_id}}">
                                                    <div class='details_name'>
                                                        <span>Detail</span>
                                                        <span>{{$detail->name}}</span>
                                                    </div>
                                                    <div class='details_price' >{{$detail->price * $order->quantity}}</div>
                                                </div>
                                            @endforeach


                                            @foreach($order->extra as $extra)
                                                <div class='extra' id="{{$extra -> extra_id}}">
                                                    <div class='extra-name'>
                                                        <span>Extra</span>
                                                        <span>{{$extra->name}}</span>
                                                    </div>
                                                    {{-- <div class='extra-price' >{{$extra->price}}</div> --}}
                                                    <div class='extra-price' >{{$extra->price * $order->quantity}}</div>
                                                </div>
                                            @endforeach
                                            @if($order->comment != null)
                                                <div class='comment'>
                                                    <div class='comment-name'>
                                                        <span>Comment</span>
                                                        <pre>{{$order->comment }}</pre>
                                                    </div>
                                                </div>
                                            @endif


                                        </div>
                                @endforeach
                                @endif
                        </div>
                    </div>

                    <!-- Footer Check  -->
                    @can("arrow check")
                    <div class="Arrow-check">
                        <i class='fas fa-angle-up fa-lg'></i>
                    </div>
                    @endcan
                    <div class='footer-check'>
                        <div class='info d-flex'>
                            <div class="left w-50">
                                <div class='items d-flex p-1 pr-3 pl-3'>
                                    <span>Items : </span>
                                    <span class='items-num text-right flex-grow-1 font-weight-bolder'>0</span>
                                </div>
                                <div class='p-1 pr-3 pl-3'>
                                    <span>Discount : </span>
                                    <span id='dis-name-check' class='text-right flex-grow-1 font-weight-bolder'>{{$dis_name}}</span>
                                </div>

                                <div class='p-1 pr-3 pl-3'>
                                    <span>Service : </span>
                                    <label class="switch ml-auto mb-0">
                                        @if($state_ser == 0)
                                            <input class="checkserviceandtax" sertaxsave="0" name="service" id='ser-check' dis="{{$taxandservice[0]['discount']}}" serTax="{{$taxandservice[0]['service_ratio']}}" type="checkbox" checked>
                                        @else
                                            <input class="checkserviceandtax" sertaxsave="{{$taxandservice[0]['service_ratio']}}"  name="service" id='ser-check' dis="{{$taxandservice[0]['discount']}}" serTax="0" type="checkbox">
                                        @endif
                                        <span class="slider round"></span>
                                      </label>
                                </div>
                                <div class='p-1 pr-3 pl-3'>
                                    <span>Tax : </span>
                                    <label class="switch ml-auto mb-0">
                                        @if($state_tax == 0)
                                            <input class="checkserviceandtax" sertaxsave="0" name="tax" id='tax-check' dis="{{$taxandservice[0]['discount']}}" serTax="{{$taxandservice[0]['tax_ratio']}}" type="checkbox" checked>
                                        @else
                                            <input class="checkserviceandtax" sertaxsave="{{$taxandservice[0]['tax_ratio']}}" name="tax" id='tax-check' dis="{{$taxandservice[0]['discount']}}" serTax="0" type="checkbox" >
                                        @endif
                                        <span class="slider round"></span>
                                      </label>
                                </div>
                            </div>
                            <div class='right w-50'>
                                <div class='total d-flex p-1 pl-3 pr-3'>
                                    <span>Total : </span>
                                    <span id='total-price' class='items-price text-right flex-grow-1 font-weight-bolder'>0.00</span>
                                </div>
                                <div class='p-1 pl-3 pr-3'>
                                    <span>Discount : </span>
                                    <span id ='dis-val-check' class='text-right flex-grow-1 font-weight-bolder'>{{$dis_val}}</span>
                                </div>
                                <div class='p-1 pl-3 pr-3'>
                                    <span>Service : </span>
                                    <span class='text-right flex-grow-1 font-weight-bolder' id='services-value'>{{$taxandservice[0]['service']}}</span>
                                </div>
                                <div class='p-1 pl-3 pr-3'>
                                    <span>Tax : </span>
                                    <span class='text-right flex-grow-1 font-weight-bolder' id='tax-value'>{{$taxandservice[0]['tax']}}</span>
                                </div>
                            </div>
                        </div>

                        <div class='footer-btns d-flex d-lg-none d-xl-none'>
                            @can('take order')
                            <a id='take_order' class='btn btn-block text-white'> Take Order </a>
                            @endcan
                            @can("change hold")
                            <a id="change_hold" href='#' class="delivery-item btn btn-warning w-100 d-none">Change Hold</a>
                            @endcan
                            @can("pay")
                            <a id="summary_check" href='#' class="delivery-item text-white btn summary_check_btn" >PAY</a>
                            @endcan
                        </div>
                    </div>

                </div>
                <div class='calculator'>
                    <form>
                        <div class='calc-header'>
                            <input id="quantity" class="calculator__display text-center" type='text' value='' disabled readonly/>
                        </div>

                        <div class="calculator__keys">
                            <button class="float thqu" data-num='.75'>3/4</button>
                            <button class="float half" data-num='.5'>1/2</button>
                            <button class="float quarter" data-num='.25'>1/4</button>
                            <button class="float eighth" data-num='.125'>1/8</button>
                            <button class="number one" data-num='1'>1</button>
                            <button class="number two" data-num='2'>2</button>
                            <button class="number three" data-num='3'>3</button>
                            <button class="number four" data-num='4'>4</button>
                            <button class="number five" data-num='5'>5</button>
                            <button class="number six" data-num='6'>6</button>
                            <button class="number seven" data-num='7'>7</button>
                            <button class="number eight" data-num='8'>8</button>
                            <button class="number nine" data-num='9'>9</button>
                            <button class="number zero" data-num='0'>0</button>
                            <button class="number decimal" data-num='.'>.</button>
                            <button class="number clear">C</button>
                            <div class='btns d-none d-lg-block d-xl-block take-pay-btn'>
                                @can("take order")
                                <a id='take_order' class='btn btn-block text-white'> Take Order </a>
                                @endcan
                                @can("change hold")
                                <a id="change_hold" href='#' class="delivery-item btn btn-warning h-100 d-none">Change Hold</a>
                                @endcan
                                @can("pay")
                                <a id="summary_check" href='#' class="delivery-item text-white summary_check_btn" >PAY</a>
                                @endcan
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <!-- End Check Out Slide -->

            <!-- Start Tabs Menu Order-->
            <div class='col-lg-8 pr-lg-0'>
                <div class='menu'>
                    @include('includes.menu.sub_header')
                    <!-- Start Tabs -->
                    <div class='sub-menu nav-tabs sticky-top d-flex'>

                        <span class='angle left d-none d-lg-flex'><i class="fas fa-angle-left"></i></span>

                        <form>
                            <ul class="nav nav-sub nav-pills" id="pills-tab" role="tablist">
                                {{ csrf_field() }} {{ method_field('POST') }}
                                @foreach($subgroup as $sub)
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="{{$sub -> name}}-tab" value="{{$sub -> id}}"  data-toggle="pill" href="#{{$sub -> name}}">
                                            <!-- <i class="fas fa-mug-hot fa-lg"></i> -->
                                            <span>{{$sub->name}}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </form>

                        <span class='angle right d-none d-lg-flex'><i class="fas fa-angle-right"></i></span>

                    </div>
                    <!-- End Tabs -->
                    <div id="subgroupnew" class='sub-group-new'>
                        <ul class='list-unstyled'>

                        </ul>

                    </div>

                    <!-- Start Tabs Content -->
                    <div class="tab-content sub-tab pt-2" id="pills-tabContent">

                        <!-- Start Drink Tab Content -->
                        <div class='row m-0 justify-content-center newrow' id="newrow">

                        </div>
                        <!-- End Drink Tab Content -->


                    </div>
                    <!-- End Tabs Content -->

                </div>

            </div>
            <!-- End Tabs Menu Order-->

        </div>

    </main>

    <!-- End Main Container -->

    @include('includes.menu.import_item')
    @include('includes.menu.customer')
    @include('includes.menu.delivery_order')
    <script>
        /* ======= Start Radio Dicount Model ============= */
        function handelRadio(myRadio) {
            $(`${myRadio}`).removeClass('d-none').siblings().addClass('d-none')
        }
        /* ======= End Radio Dicount Model ============= */

        /* ======= Start Discount Check With Confirem ============= */
        function checkDiscountConfirm() {

            let discounts = $('#discount-on-all-check .discount-item').length

            if (discounts > 0) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You have Discount on  items in check",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#discount-on-all-check').modal();
                    }
                });
            } else {
                $('#discount-on-all-check').modal();
            }
        }
        /* ======= End Discount Check With Confirem ============= */
    </script>
@stop
