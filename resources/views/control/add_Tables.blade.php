@php
$title = 'Add Tables';
@endphp

@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section style='min-height: calc(100vh - 3rem)'>

    <h2 class="section-title"> Tables </h2>

    <div class="col-md-4 mx-auto">
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
    </div>



    <!-- Start Tables -->
    <section class='tables d-flex' style='min-height: calc(100vh - (3rem + 105px))'>

        <div class='btn-toggle-form ml-auto mr-3'>
            <i class="fa fa-cog fa-2x"></i>
        </div>

        <div class='tabs w-100'>

            <ul class="nav nav-tabs tabs-tables" id="holes" role="tablist"></ul>

            <div class="tab-content h-100" id="holesContent"></div>

        </div>

        <div class='form-tables'>
            <form>
                @csrf
                <div class="create-hole">
                    <input id='hole_name' type='text' placeholder='Type Hole Name' />
                    <input id='min_hole' type='number' placeholder='Type min tables in hole' />
                    <input id='max_hole' type='number' placeholder='Type max tables in hole' />

                    <button type="submit"  class='create-hole' id="save_hole">Create Hole</button>
                </div>

                <div class="create-table">
                    <input id='copies' type='number' placeholder='Tables Number' value='' />
                    <label for='circle'>Circle</label>
                    <input type="checkbox" id='circle'/>
                    <input id='chair-num' type='number' placeholder='Chair Number' value='' />
                    <button type="submit"  class='create-table' id="save_table">Create Table</button>
                </div>

                <div class="create-other-table d-none">
                    <input type="text" id="other_name" placeholder='Table Name'>
                    <button type="submit"  id="save_other">Create Table</button>
                </div>
            </form>
        </div>

    </section>
    <!-- End Tables -->
@include('includes.control.tables')
@stop
