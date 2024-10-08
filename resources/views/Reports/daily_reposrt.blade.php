@php
$title = 'Daily Report';
@endphp

@extends('layouts.menu')
@section('content')
@include('includes.menu.sub_header')
<section class='check-out px-4 py-5'>
    @csrf
    <button type="button" class="btn btn-primary filter" data-toggle="modal" data-target="#report-filter">
        Filters
    </button>
    <div id="report-output">
    </div>
</section>


<div class="modal report-loader fade" id="report-loader" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="loader" id="loader">Loading...</div>
</div>

<div class="modal report-filter fade" id="report-filter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="col-md-9">
                    <form>
                        <div class="select-container">
                            <label for="time-input" class="mb-0">From</label>
                            <input type="date" class="form-control select-report" id="from" value="<?php echo date('Y-m-d', strtotime("-1 days")); ?>" max="<?php echo date('Y-m-d', strtotime("-1 days")); ?>" dataformatas="dd/mm/yyyy">
                        </div>
                        <div class="select-container">
                            <label for="time-input" class="mb-0">To</label>
                            <input type="date" class="form-control  select-report" id="to" value="<?php echo date('Y-m-d', strtotime("-1 days")); ?>" max="<?php echo date('Y-m-d', strtotime("-1 days")); ?>" dataformatas="dd/mm/yyyy">
                        </div>
                        <div class="filter-check">
                            <h5 class="dropdown-toggle" data-toggle="collapse" data-target="#transaction">Transaction</h5>
                            <div class="collapse" id='transaction'>
                                <div class="d-flex pl-3">
                                    <div class="form-group m-0">
                                        <input type="checkbox" id="transTable" name="transaction" value="Table">
                                        <label for="transTable"> Table </label>
                                    </div>
                                    <div class="form-group m-0">
                                        <input type="checkbox" id="transDelivery" name="transaction" value="Delivery">
                                        <label for="transDelivery"> Delivery </label>
                                    </div>
                                    <div class="form-group m-0">
                                        <input type="checkbox" id="transTO_GO" name="transaction" value="TO_GO">
                                        <label for="transTO_GO"> TO-GO </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="filter-check">
                            <h5 class="dropdown-toggle" data-toggle="collapse" data-target="#bay_way">Bay Way</h5>
                            <div class="collapse" id='bay_way'>
                                <div class="d-flex pl-3">
                                    <div class="form-group m-0">
                                        <input type="checkbox" id="bayCash" name="bay_way" value="cash">
                                        <label for="bayCash"> Cash </label>
                                    </div>
                                    <div class="form-group m-0">
                                        <input type="checkbox" id="bayVisa" name="bay_way" value="credit">
                                        <label for="bayVisa"> credit </label>
                                    </div>
                                    <div class="form-group m-0">
                                        <input type="checkbox" id="bayHospitality" name="bay_way" value="hospitality">
                                        <label for="bayHospitality"> Hospitality </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="filter-check">
                            <h5 class="dropdown-toggle" data-toggle="collapse" data-target="#shift">Shift</h5>
                            <div class="collapse" id='shift'>
                                <div class="d-flex pl-3">
                                    @foreach($shifts as $shift)
                                    <div class="form-group m-0">
                                        <input type="checkbox" id="shift{{$shift->shiftid}}" name="Shift" value="{{$shift->shiftid}}">
                                        <label for="shift{{$shift->shiftid}}"> {{$shift->shift}} </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="filter-check">
                            <h5 class="dropdown-toggle" data-toggle="collapse" data-target="#user">User</h5>
                            <div class="collapse" id='user'>
                                <div class="d-flex pl-3">
                                    @foreach($users as $user)
                                    <div class="form-group m-0">
                                        <input type="checkbox" id="user{{$user->id}}" name="User" value="{{$user->id}}">
                                        <label for="user{{$user->id}}"> {{$user->name}} </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="filter-check">
                            <h5 class="dropdown-toggle" data-toggle="collapse" data-target="#device">Device</h5>
                            <div class="collapse" id='device'>
                                <div class="d-flex pl-3">
                                    @foreach($devices as $device)
                                    <div class="form-group m-0">
                                        <input type="checkbox" id="device{{$device->id_device}}" name="Device" value="{{$device->id_device}}">
                                        <label for="device{{$device->id_device}}"> {{$device->id_device}} </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="filter-check">
                            <h5 class="dropdown-toggle" data-toggle="collapse" data-target="#addition">Addition</h5>
                            <div class="collapse" id='addition'>
                                <div class="d-flex pl-3">
                                    <div class="form-group m-0">
                                        <input type="checkbox" id="AdditionService" name="Addition" value="state_service">
                                        <label for="AdditionService"> No-Service </label>
                                    </div>
                                    <div class="form-group m-0">
                                        <input type="checkbox" id="AdditionTax" name="Addition" value="state_tax">
                                        <label for="AdditionTax"> No-Tax </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="filter-check">
                            <h5 class="dropdown-toggle" data-toggle="collapse" data-target="#ex_de">Details&Extra</h5>
                            <div class="collapse" id='ex_de'>
                                <div class="d-flex pl-3">
                                    <div class="form-group m-0">
                                        <input type="checkbox" id="ex_de_Detail" name="ex_de" value="Details">
                                        <label for="ex_de_Detail"> With Detail </label>
                                    </div>
                                    <div class="form-group m-0">
                                        <input type="checkbox" id="ex_de_Extra" name="ex_de" value="Extra">
                                        <label for="ex_de_Extra"> With Extra </label>
                                    </div>
                                    <div class="form-group m-0">
                                        <input type="checkbox" id="ex_de_all" name="ex_de" value="all">
                                        <label for="ex_de_all"> Sold Items Only </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="filter-check">
                            <h5 class="dropdown-toggle" data-toggle="collapse" data-target="#sort">Sold Sort</h5>
                            <div class="collapse" id='sort'>
                                <div class="d-flex pl-3">
                                    <div class="form-group m-0">
                                        <input type="checkbox" id="sort_reverse">
                                        <label for="sort_reverse"> Reverse </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="reset" value="Reset" class="btn btn-primary mt-2">
                    </form>
                </div>
                <div class="col-md-3 d-flex flex-column">
                    <button class="btn btn-success my-2" id="daily_report">Daily Report</button>
                    <button class="btn btn-success my-2" id="sold_items">Sold Items</button>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.reports.daily_reports')
@stop