@php
    $title = 'Device';
@endphp

@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section class='accordions-sec'>
    <div class="container">
        <h2 class="section-title">Add Device</h2>
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                <div class="select-box">
                    <select class="select_Branch" name="branch" id="select">
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

                <form class="form-inline">
                    @csrf
                    <div class="form-element">
                        <label for="device_id" class="input-label">Number Device</label>
                        <input type="text" class="mycustom-input" id="device_id" name="device">
                        <span class='under_line'></span>
                    </div>
                </form>

                <div class="select-box">
                            <select class="select_printers" id="printer">
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
                        <button type="submit" id="add_device" class="btn btn-block btn-success mt-3"> Add </button>

                
            </div>

        </div>

    </div>
</section>
@include('includes.control.device')
@stop
