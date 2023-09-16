@php
$title = 'Extra';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section>
    <div class='container'>
        <h2 class="section-title"> Items Extra </h2>

        <div class='row'>
            <div class='col-xl-8 offset-xl-2 col-lg-10 offset-lg-1'>
                @csrf
                <div class='row'>
                    <div class='col-md-6'>

                        <div class="select-box">
                            <select class="select_Branch" name="branch" id="select_branch">
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

                        <div class="select-box">

                            <select class="select_menu" name="menu" id="select_menu">

                            </select>

                            <div class='search-select'>
                                <div class='label-Select'>
                                    <label for='menu-input'>Chose Menu...</label>
                                    <input autocomplete="off" type='text' class="search-input" id='menu-input' />
                                    <i class='arrow'></i>
                                    <span class='line'></span>
                                </div>

                                <div class='input-options'>
                                    <ul></ul>
                                </div>

                            </div>

                        </div>

                        <div class="select-box">

                            <select class="select_group" name="group" id="select_group">

                            </select>

                            <div class='search-select'>
                                <div class='label-Select'>
                                    <label for='group-input'>Chose Group...</label>
                                    <input autocomplete="off" type='text' class="search-input" id='group-input' />
                                    <i class='arrow'></i>
                                    <span class='line'></span>
                                </div>

                                <div class='input-options'>
                                    <ul></ul>
                                </div>

                            </div>

                        </div>

                        <div class="select-box">

                            <select class="select_subgroup" name="subgroup" id="select_subgroup"></select>

                            <div class='search-select'>
                                <div class='label-Select'>
                                    <label for='subgroup-input'>Chose SubGroup...</label>
                                    <input autocomplete="off" type='text' class="search-input" id='subgroup-input' />
                                    <i class='arrow'></i>
                                    <span class='line'></span>
                                </div>

                                <div class='input-options'>
                                    <ul></ul>
                                </div>

                            </div>

                        </div>

                        <div class="select-box">

                            <select class="select_items_sub" name="select_items" id="select_items_sub"></select>

                            <div class='search-select'>
                                <div class='label-Select'>
                                    <label for='select_items-input'>Chose Item...</label>
                                    <input autocomplete="off" type='text' class="search-input"
                                        id='select_items-input' />
                                    <i class='arrow'></i>
                                    <span class='line'></span>
                                </div>

                                <div class='input-options'>
                                    <ul></ul>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class='col-md-6'>

                        <div class="form-element">
                            <label class='input-label' for="extra_search">Search Extra</label>
                            <input name="extra_search" class='mycustom-input' type="text" id="extra_search" />
                            <span class='under_line'></span>
                        </div>

                        <select id="view_extra" class='w-100 multi-section' name="extra" multiple></select>
                    </div>

                    <div class='col-md-6 offset-md-3 my-4'>
                        <button class='btn btn-block btn-success' id="export_extra">Export</button>
                    </div>

                    <div class="table-responsive">
                        @csrf
                        <table id="editable" class="table table-bordered table-striped" font_Size="15">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Operation</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>
<!--     @include('includes.control.add_genral_ajax');

 -->
@include('includes.control.add_item_ajax')
@include('includes.control.main')
@stop
