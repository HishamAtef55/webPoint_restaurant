@php
    $title = 'Locations';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section class='accordions-sec'>
    <div class="container">
                <h2 class='section-title'> Locations </h2>
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                <div class='custom-grid-delivery'>
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
                    <div class='form-element'>
                        <label class='input-label' for="location">Location</label>
                        <input type="text" class="mycustom-input" id="location" name="location" />
                        <span class='under_line'></span>
                    </div>
                    <div class='form-element'>
                        <label class='input-label' for="price">Price</label>
                        <input type="number" class="mycustom-input" id="price" name="price" />
                        <span class='under_line'></span>
                    </div>
                    <div class='form-element'>
                        <label class='input-label' for="time">Time</label>
                        <input type="number" class="mycustom-input" id="time" name="time" />
                        <span class='under_line'></span>
                    </div>
                    <div class='form-element'>
                        <label class='input-label' for="pilotValue">Pilot Value</label>
                        <input type="number" class="mycustom-input" id="pilotValue" name="pilotValue" />
                        <span class='under_line'></span>
                    </div>
                </div>
                <div class='col-md-6 offset-md-3 mb-3 pb-3 mt-3'>
                    <button id="savelocation" type="submit" class="btn btn-block btn-success">Save</button>
                </div>
                @csrf
                <div class='table-responsive'>
                    <table id="editable" class="table table-bordered table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Location</th>
                            <th scope="col">Price</th>
                            <th scope="col">Time</th>
                            <th scope="col">Pilot Value</th>
                        </tr>
                        </thead>
                        <tbody id="tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<div>
@include('includes.control.location')
@stop
