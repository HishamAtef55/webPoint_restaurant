
@php
    $title = 'Expenses';
@endphp

@extends('layouts.menu')
@section('content')
@include('includes.menu.sub_header')
    <input type="hidden" id="device_id" value="">
    <section class='moveTo'>
        <div class='container'>
            <div class="alert alert-success"  style="display: none;" id="alert_show" role="alert">
                Successful Move <script>setTimeout(function(){$('#alert_show').hide();}, 2500);</script>
            </div>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#exampleModal">
                Create Expenses
            </button>
            <div class="row mt-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Category</th>
                            <th scope="col">Amount</th>
                            <th scope="col">User</th>
                            <th scope="col">Note</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $row)
                            <tr>
                                <td>{{$row->id}}</td>
                                <td>{{$row->date}}</td>
                                <td>{{$row->time}}</td>
                                <td>{{$row->category->title}}</td>
                                <td>{{$row->amount}}</td>
                                <td>{{$row->user->email}}</td>
                                <td>{{$row->note}}</td>
                                <td>
                                    <button class="btn btn-danger">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Create Expenses</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class='row mb-4'>
                        <div class="col-md-12 d-flex flex-row mt-3">
                            <div class="form-element w-100">
                                <h6 class='text-center text-white'>category</h6>
                                @csrf
                                <select  class="main_table custom-select"  name="search_main_table" id="search_main_table">
                                    <option selected disabled>Choose category...</option>
                                    @foreach($category as $row)
                                        <option value="{{$row->id}}">{{$row->title}}</option>
                                    @endforeach
                                </select>
                                {{-- <div class='search-select'>
                                    <div class='label-Select'>
                                        <label for='main-input'>Main Table...</label>
                                        <input type='text' class="search-input" id='main-input' autocomplete="off"/>
                                        <i class='arrow'></i>
                                        <span class='line'></span>
                                    </div>
                                    <div class='input-options'>
                                        <ul id="search_main_table"></ul>
                                    </div>
                                </div>  --}}
                            </div>
                        </div>
                    </div>
                    <div class='row mb-4'>
                        <div class="col-md-12 d-flex flex-row mt-3">
                            <div class="form-element w-100">
                                <h6 class='text-center text-white'>Amount</h6>
                                <input id="amount" name="amount" type="number" class="form-control use-keyboard-input">
                            </div>
                        </div>
                    </div>
                    <div class='row mb-4'>
                        <div class="col-md-12 d-flex flex-row mt-3">
                            <div class="form-element w-100">
                                <h6 class='text-center text-white'>Note</h6>
                                <textarea id="note" name="note" class="form-control use-keyboard-input"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="save">Create</button>
                </div>
              </div>
            </div>
          </div>

    </section>
    @include('includes.menu.Expenses')
@stop



