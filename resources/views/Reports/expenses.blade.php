@php
    $title = 'Void Report';
@endphp

@extends('layouts.menu')
@section('content')
    @include('includes.menu.sub_header')
    <section class='check-out px-4 py-5'>
        @csrf

        <div class="container">
            <button type="button" class="btn btn-primary filter" data-toggle="modal" data-target="#report-filter">
                Filters
            </button>
            <div id="report-output"></div>
        </div>

    </section>
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
                            <input type="date" class="form-control select-report" id="from" value="<?php echo date('Y-m-d',strtotime("0 days"));?>"  max="<?php echo date('Y-m-d',strtotime("0 days"));?>" dataformatas="dd/mm/yyyy">
                        </div>
                        <div class="select-container">
                            <label for="time-input" class="mb-0">To</label>
                            <input type="date" class="form-control  select-report" id="to" value="<?php echo date('Y-m-d',strtotime("0 days"));?>"  max="<?php echo date('Y-m-d',strtotime("0 days"));?>" dataformatas="dd/mm/yyyy">
                        </div>
                        <hr>
                        <div class="select-container">
                            <label for="bay_way">Type</label>
                            <select id="category" class="custom-select select-report">
                                <option value="all">All</option>
                                @foreach($category as $row)
                                    <option value="{{$row->id}}">{{$row->title}}</option>
                                @endforeach
                            </select>
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
                        <input type="reset" value="Reset" class="btn btn-primary mt-2">
                    </form>
                </div>
                <div class="col-md-3 d-flex flex-column">
                    <button class="btn-report-in-day btn btn-success my-2" id="expenses_report">View Report</button>
                </div>
            </div>
          </div>
        </div>
      </div>
    @include('includes.reports.general_reports')
@stop

