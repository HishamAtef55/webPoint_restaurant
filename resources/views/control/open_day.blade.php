@php
    $title = 'Open Day';
@endphp
@extends('layouts.menu')
@section('content')
    @include('includes.menu.sub_header')
<section class='accordions-sec'>
    <div class="container">
    <div class="loading" id="loading">
        <div class="loadingio-spinner-double-ring-sp9kmd43d3d">
            <div class="ldio-9fnosy7o1v">
                <div></div>
                <div></div>
                <div>
                    <div></div>
                </div>
                <div>
                    <div></div>
                </div>
            </div>
        </div>
        <h3>Loading...</h3>
    </div>


    <div class="modal report-loader fade" id="report-loader" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="loader" id="loader">Loading...</div>
    </div>

        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 text-wight">
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

                <div class="select-box">

                    <select class="select_menu" name="branch" id="select_menu">

                    </select>

                    <div class='search-select'>
                        <div class='label-Select'>
                            <label for='menu-input'>Choose Menu...</label>
                            <input autocomplete="off" type='text' class="search-input" id='menu-input' />
                            <i class='arrow'></i>
                            <span class='line'></span>
                        </div>

                        <div class='input-options'>
                            <ul></ul>
                        </div>

                    </div>

                </div>
                @csrf
                <button type="submit" id="openDay" class="btn btn-block btn-success mt-3"> Open Day </button>
                <button type="submit" id="EmptyTable" class="btn btn-block btn-danger mt-3"> Empty Tables </button>
            </div>

        </div>

    </div>
</section>
@include('includes.control.open_day')
@stop
