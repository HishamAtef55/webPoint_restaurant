@php
$title = 'Printers';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section>
    <div class='container'>
        <h2 class='section-title'> Printers </h2>
        <div class='row'>
            <div class='col-md-8 offset-md-2'>
                <div class="select-box">
                    <select class="select_Branch" name="branch" id="select">
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
                <div class="d-flex my-4">
                    <div class="form-element m-0">
                        <label class='input-label' for="printer-name">
                            <i class="fas fa-search"></i>
                            Search for Printers
                        </label>
                        <input autocomplete="off" name="printer_name" class='mycustom-input' type="text" id="printer-name" />
                        <span class='under_line'></span>
                    </div>
                    <button id="save_printer" class="btn btn-primary ml-2" type="button"><i class="fas fa-plus"></i></button>
                </div>
                @csrf
                <div class="table-responsive">
                    <table id="editable" class="table table-bordered table-striped" font_Size="15">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                            </tr>
                        </thead>
                        <tbody id="tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@include('includes.control.printerrs')
@stop
