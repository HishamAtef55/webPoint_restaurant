@php
$title = 'Menu';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')


<section class='accordions-sec'>
    <div class="container">

        <h2 class="section-title">Menu</h2>

        <div class="row">
            <div class="col-lg-10 offset-lg-1 col-md-12">

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

                <div class="d-flex my-4">
                    <div class="form-element m-0">
                        <label for="search" class="input-label"><i class="fas fa-search"></i> Search for Menu</label>
                        <input autocomplete="off" type="text" name="search" id="search" class="mycustom-input">
                        <span class='under_line'></span>
                    </div>
                    <button id="save_menu" class="btn btn-primary ml-2" type="button"><i class="fas fa-plus"></i></button>
                </div>
                    
                @csrf

                <div class="table-responsive">
                    <table id="editable" class="table table-bordered table-striped" font_Size="15">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Branch</th>
                                <th scope="col">Name</th>
                                <th scope="col">Activation</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            @foreach($data as $ex)
                                <tr id="{{$ex->id}}">
                                    <td>
                                        <span class="tabledit-span tabledit-identifier">{{$ex->id}}</span>
                                        <input id="id" class="tabledit-input tabledit-identifier" type="hidden" name="id" value="{{$ex->id}}">
                                    </td>
                                    
                                    <td class="">
                                        <span class="tabledit-span tabledit-identifier">{{$ex->branch->name}}</span>
                                        <input id="branch_id" class="tabledit-input tabledit-identifier" type="hidden" name="branch_id" value="{{$ex->branch->name}}">
                                    </td>

                                    <td class="tabledit-edit-mode">
                                        <span class="tabledit-span tabledit-identifier">{{$ex->title}}</span>
                                        <input type="text" style="display: none;" disabled class="tabledit-input form-control input-sm" name="name" value="{{$ex->title}}">
                                    </td>
                
                                    <td>
                                        <div class="tabledit-toolbar btn-toolbar">
                                            <div class="btn-group btn-group-sm">
                                            <button type="button" id='edit-btn' class="tabledit-edit-button btn btn-default">
                                                <span><i class="far fa-edit fa-lg"></i></span>
                                            </button>
                                            <button type="button" class="tabledit-delete-button btn btn-default">
                                                <span><i class="fas fa-trash fa-lg"></i></span>
                                            </button>
                                            </div>
                                            <button type="button" class="tabledit-save-button btn btn-sm btn-success" style="display: none;">Confirm</button><button type="button" class="tabledit-confirm-button btn btn-sm btn-danger" style="display: none;">Confirm</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>
@include('includes.control.Expenses')
@stop