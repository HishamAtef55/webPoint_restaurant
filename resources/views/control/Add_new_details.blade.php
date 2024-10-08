@php
$title = 'Add New Details';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section>
    <div class='container'>
        <div class='row'>
            <!-- Start Items Accordion -->
            <div class='col-md-10 offset-md-1'>

                <h2 class="section-title"> Items Details</h2>

                <form method="POST" action=" " id="form_data">
                    @csrf
                    <div class='row'>

                        <div class='col-md-6'>

                            <div class="select-box">

                                <select class="select_Branch" name="select" id="select_branch">
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

                                <select class="select_and_Search_chose_itemorsub" name="select" id="select">
                                    <option disabled selected></option>
                                    <option value="1">Items</option>
                                    <option value="2">Sub Group</option>
                                </select>

                                <div class='search-select'>
                                    <div class='label-Select'>
                                        <label for='chose-input'>Sub Group / Items ....</label>
                                        <input autocomplete="off" type='text' class="search-input" id='chose-input' />
                                        <i class='arrow'></i>
                                        <span class='line'></span>
                                    </div>

                                    <div class='input-options'>
                                        <ul></ul>
                                    </div>

                                </div>

                            </div>

                            <div class="select-box">

                                <select class="select_and_Search_items_details" name="items" id="select_data"> </select>

                                <div class='search-select'>
                                    <div class='label-Select'>
                                        <label for='item-input'>Choose Item ....</label>
                                        <input autocomplete="off" type='text' class="search-input" id='item-input' />
                                        <i class='arrow'></i>
                                        <span class='line'></span>
                                    </div>

                                    <div class='input-options'>
                                        <ul></ul>
                                    </div>

                                </div>

                            </div>



                        </div>

                        <div class="col-md-6">

                            <div class='d-flex my-3'>
                                <div class="form-element m-0 input-empty">
                                    <label for="details-name" class="input-label">Item Details name</label>
                                    <input type="text" name="name_details" class="mycustom-input" id="details-name">
                                    <span class='under_line'></span>
                                </div>
                                <button type="submit" id="save_new_details" class="btn btn-primary ml-2"><i
                                        class="fas fa-plus"></i></button>
                            </div>

                            <select id="select_datatable" class='w-100 multi-section' name="details[]"
                                multiple></select>

                            <div class="d-flex">

                                <div class="form-element flex-grow-1" style='margin: 13px 0'>
                                    <label for="section_" class="input-label">Section</label>
                                    <input type="text" name="section" class="mycustom-input" id="section_" />
                                    <span class='under_line'></span>
                                </div>

                                <div class='form-element flex-grow-1 ml-3' style='margin: 13px 0'>
                                    <label for="max" class="input-label"> Max </label>
                                    <input type="number" name="max" class="mycustom-input" id="max"  />
                                    <span class='under_line'></span>
                                </div>

                            </div>



                        </div>

                        {{-- <div class="col-md-6">
                            <select id="select_datatable" class='w-100' name="details[]" multiple>
                                <option disabled selected></option>
                            </select>

                            <small id="details_error" class="form-text text-danger"></small>
                        </div> --}}

                        <div class='col-md-6 offset-md-3 my-4'>
                            <button class='btn btn-block btn-success' id='add_to_table'>Add</button>
                        </div>

                    </div>
                </form>

            </div>
            <!-- End Items Accordion -->

            <div class="table-responsive" id='table-section'>

                @csrf
                <table id="editable" class="table table-bordered table-striped" font_Size="15">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Section</th>
                            <th scope="col">Max</th>
                            <th scope="col">SubGroup/Item</th>
                            <th scope="col">Operation</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>

        </div>
    </div>
</section>
@include('includes.control.add_itemdetals_ajax');
@stop
