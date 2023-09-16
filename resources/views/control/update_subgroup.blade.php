@php
$title = 'SubGroup';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section class='accordions-sec'>
    <div class="container">

        <h2 class="section-title">Sub Group</h2>


        <div class="row">
            <div class="col-lg-10 offset-lg-1 col-md-12">
                <div class="select-box">
                    <select class="select_Branch" name="branch" id="select_branch">
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

                <div class="select-box">

                    <select class="select_group" name="group" id="select_group">

                    </select>

                    <div class='search-select'>
                        <div class='label-Select'>
                            <label for='group-input'>Choose Group...</label>
                            <input autocomplete="off" type='text' class="search-input" id='group-input' />
                            <i class='arrow'></i>
                            <span class='line'></span>
                        </div>

                        <div class='input-options'>
                            <ul></ul>
                        </div>

                    </div>

                </div>

                <div class="d-flex my-4">
                    <div class="form-element m-0">
                        <label for="search" class="input-label"><i class="fas fa-search"></i> Search for Sub Group</label>
                        <input autocomplete="off" type="text" name="search" id="search" class="mycustom-input">
                        <span class='under_line'></span>
                    </div>
                    <button id="save_subgroup" class="btn btn-primary ml-2" type="button"><i class="fas fa-plus"></i></button>
                </div>

                @csrf

                <div class="table-responsive">
                    <table id="editable" class="table table-bordered table-striped" font_Size="15">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Menu</th>
                                <th scope="col">Activation</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">

                        </tbody>
                    </table>
                </div>


            </div>

        </div>
    </div>






    </div>
</section>
@include('includes.control.main')
@include('includes.control.sub_group')
@stop
