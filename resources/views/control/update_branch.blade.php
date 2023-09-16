@php
$title = 'Branch';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section class='accordions-sec'>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                <div class="d-flex my-4">
                    <div class="form-element m-0">
                        <label for="search" class="input-label"><i class="fas fa-search"></i> Search for Branch</label>
                        <input autocomplete="off" type="text" name="search" id="search" class="mycustom-input">
                        <span class='under_line'></span>
                    </div>
                    <button id="save_branch" class="btn btn-primary ml-2" type="button"><i
                            class="fas fa-plus"></i></button>
                </div>

                @csrf

                <div class='table-responsive'>
                    <table id="editable" class="table table-bordered table-striped">
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
@include('includes.control.branch')
@stop
