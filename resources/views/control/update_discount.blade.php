@php
$title = 'Discount';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section>
    <div class='container'>

        <h2 class="section-title"> Add Discount </h2>

        <div class='row'>

            <div class="col-md-4">

                <form method="POST" action=" " id="Save_form_dis" class='mb-3'>
                    <div class="form-element">

                        <div class="select-box">

                            <select class="select_Branch" name="branch" id="branch">
                                <option value=""></option>
                                @foreach($branchs as $branch)
                                <option value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>

                            <div class='search-select'>
                                <div class='label-Select'>
                                    <label for='branch-input'>Chose Branch...</label>
                                    <input autocomplete="off" type='text' class="search-input" id='branch-input' />
                                    <i class='arrow'></i>
                                    <span class='line'></span>
                                </div>

                                <div class='input-options'>
                                    <ul></ul>
                                </div>

                            </div>

                        </div>
                        @csrf

                    </div>

                    <div class='form-element input-empty'>
                        <label class='input-label' for="discount-type"> Discounts Name </label>
                        <input autocomplete="off" name="name" type="text" class="mycustom-input" id="discount-type" />
                        <span class='under_line'></span>
                    </div>

                    <div>
                        <div class="shadow p-3 rounded d-block"> Discount Type </div>

                        <div class='radio-box p-3 shadow'>

                            <div class="form-check">
                                <input value="Ratio" class="form-check-input" type="radio" name="discount_type"
                                    id="dis-ratio" data-value="ratio">
                                <label class="form-check-label" for="dis-ratio">Discount Ratio</label>
                            </div>

                            <div class="form-check mt-2">
                                <input value="Value" class="form-check-input" type="radio" name="discount_type"
                                    id="dis-value" data-value="value">
                                <label class="form-check-label" for="dis-value">Discount Value</label>
                                <small id="discount_type_error" class="form-text text-danger"></small>
                            </div>

                        </div>

                        <div class="form-element input-empty">
                            <label class="input-label" for="enter-value">Enter Value</label>
                            <input class="mycustom-input" type="number" name="value" id="enter-value">
                            <span class='under_line'></span>
                        </div>

                    </div>

                    <div class='d-flex'>
                        <button id="save_discount" type="submit" class="btn btn-success btn-block">Save</button>
                    </div>

                </form>

            </div>

            <div class="col-md-8">
                <div class="table-responsive">
                    <div class="d-flex my-4">
                        <div class="form-element m-0">
                            <label for="search" class="input-label"><i class="fas fa-search"></i> Search for
                                Discount</label>
                            <input autocomplete="off" type="text" name="search" id="search" class="mycustom-input">
                            <span class='under_line'></span>
                        </div>
                        <!-- <button id="save_menu" class="btn btn-primary ml-2" type="button">Save</button> -->
                    </div>

                    @csrf
                    <table id="editable" class="table table-bordered table-striped" font_Size="15">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Type</th>
                                <th scope="col">Value</th>
                                <th scope="col">Branch</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>
@include('includes.control.discount')
@stop
