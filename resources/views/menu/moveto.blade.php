
@php
    $title = 'MoveTO';
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
            <div class='row mb-4 table-num'>
                <div class="col-md-5 d-flex flex-row mt-3">
                    <div class="form-element w-100">
                        <h2 class='text-center text-white'>From</h2>
                        @csrf
                        <select  class="main_table custom-select"  name="search_main_table" id="search_main_table">
                            <option selected disabled>Choose Table...</option>
                            @foreach($master_tables as $table)
                                <option value="{{$table->number_table}}">{{$table->number_table}}</option>
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
                <div class="col-md-5 offset-md-2 d-flex flex-row mt-3">
                    <div class="form-element w-100">
                        <h2 class='text-center text-white'>To</h2>
                        <select  class="new_table custom-select"  name="new_table" id="search_new_table">
                            <option selected disabled>Choose Table...</option>
                            @foreach($tables as $table)
                            <option value="{{$table->number_table}}">{{$table->number_table}}</option>
                            @endforeach
                        </select>
                        {{-- <div class='search-select'>
                            <div class='label-Select'>
                                <label for='new-input'>New Table...</label>
                                <input type='text' class="search-input" id='new-input' autocomplete="off"/>
                                <i class='arrow'></i>
                                <span class='line'></span>
                            </div>
                            <div class='input-options'>
                                <ul id="search_new_table"></ul>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class='row lists'>
                <div class='col-6 col-md-5'>
                    <div class="left">
                            <ul class="table-list list-unstyled">
                                <div id="new_order_view">

                                </div>
                            </ul>
                    </div>
                </div>
                <div class='col-6 col-md-5'>
                    <div class="right">
                        <ul class="table-list list-unstyled">
                            <div id="main_order_view">

                            </div>
                        </ul>
                    </div>
                </div>
                <div class='col col-md-2'>
                    <div class="arrow">
                        <button id="transfer" class='btn btn-block btn-primary py-5 trans-all'>Transfer</button>
                        <a  href="{{Route('view.table')}}" class='btn btn-block btn-danger'>Cancel</a>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @include('includes.menu.move_to')
    {{-- <script>
        $('.moveTo').height($(window).outerHeight() - ($('.navbar').outerHeight() + $('footer').outerHeight() ));

        $(window).on('resize', function() {
          $('.moveTo').height($(window).outerHeight() - ($('.navbar').outerHeight() + $('footer').outerHeight() ));
        });

      </script> --}}
@stop



