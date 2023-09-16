@php
    $title = 'Information';
@endphp

@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section class='accordions-sec'>
    <div class="container">
        <h2 class="section-title">Information</h2>
        <form id="form_save_resturant" action="" method="POST" multiple enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8 offset-md-2">
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-element input-empty">
                                <label class='input-label' for="res-name"> Resturant Name </label>
                                <input autocomplete="off" name="name" class='mycustom-input' type="text" id="res-name" />
                                <span class='under_line'></span>
                            </div>

                            <div class="form-element input-empty">
                                <label class='input-label' for="res-phone"> Resturant Phone </label>
                                <input autocomplete="off" name="phone" class='mycustom-input' type="number" id="res-phone" />
                                <span class='under_line'></span>
                            </div>

                            <div class="form-element h-auto input-empty">
                                <label class='input-label' for="note">Note</label>
                                <textarea class='mycustom-input' id="note" name="note" type="text"></textarea>
                                <span class='under_line'></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Upload Image -->
                            <div class='text-center'>
                                <i class="file-image">
                                    <input autocomplete="off" id="res-image" name="image" type="file" onchange="readImage(this)" title="" />
                                    <i class="reset" onclick="resetImage(this.previousElementSibling)"></i>
                                    <div class="res-image">
                                        <label for="res-image" class="image" data-label="Resturant Image"></label>
                                    </div>
                                </i>
                            </div>

                            <div class='text-center'>
                                <i class="file-image">
                                    <input autocomplete="off" id="slogan-image" name="slogan" type="file" onchange="readImage(this)" title="" />
                                    <i class="reset" onclick="resetImage(this.previousElementSibling)"></i>
                                    <div class="slogan-image">
                                        <label for="slogan-image" class="image" data-label="Slogan Image"></label>
                                    </div>
                                </i>
                            </div>
                        </div>
                        <div class="col-md-6 offset-md-3">
                            <button id="save_res" type="submit" class="btn-block btn btn-success"> Save </button>
                            <button id="update_res" type="submit" class="btn-block btn btn-primary d-none"> Update </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@include('includes.control.information')
@stop
