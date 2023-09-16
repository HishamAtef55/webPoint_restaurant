@php
$title = 'Items';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section>
    <div class='container'>
        <h2 class="section-title"> Item </h2>


        <form id="form_save_item" action=" " method="POST" multiple enctype="multipart/form-data">
            @csrf

            <div class='row'>

                <div class='col-md-7'>

                    <div class="d-flex align-items-center">
                        <div class="select-box flex-grow-1 mr-4">
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

                        <div class="select-box flex-grow-1">

                            <select class="select_menu" name="menu" id="select_menu"></select>

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
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="select-box flex-grow-1 mr-4">

                            <select class="select_group" name="group" id="select_group"></select>

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

                        <div class="select-box flex-grow-1">

                            <select class="select_subgroup" name="subgroup" id="select_subgroup"></select>

                            <div class='search-select'>
                                <div class='label-Select'>
                                    <label for='subgroup-input'>Choose SubGroup...</label>
                                    <input autocomplete="off" type='text' class="search-input" id='subgroup-input' />
                                    <i class='arrow'></i>
                                    <span class='line'></span>
                                </div>

                                <div class='input-options'>
                                    <ul></ul>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="form-element input-empty m-0">
                        <label class='input-label' for="item-name"> Item Name </label>
                        <input autocomplete="off" id-item="" class='mycustom-input' name="name" type="text" id="item-name" />
                        <input autocomplete="off" class='mycustom-input d-none' name="id" type="text" id="item-id" />
                        <input autocomplete="off" class='mycustom-input d-none' name="realname" type="text" id="realname" />
                        <span class='under_line'></span>
                        <ul id="list-of-items" class="list-of-items"></ul>
                    </div>

                    <div class="form-element my-4 input-empty">
                        <label class='input-label' for="item-slep-name">Item Slep Name</label>
                        <input autocomplete="off" class='mycustom-input' name="slep_name" type="text" id="item-slep-name" />
                        <span class='under_line'></span>
                    </div>

                    <div class="form-element input-empty">
                        <label class='input-label' for="item-chick-name">Item Chick Name</label>
                        <input autocomplete="off" class='mycustom-input' name="chick_name" type="text" id="item-chick-name" />
                        <span class='under_line'></span>
                    </div>

                    <div class='custom-grid'>

                        <div class="form-element input-empty">
                            <label class='input-label' for="table-price"> Table Price </label>
                            <input autocomplete="off" class='mycustom-input' name="price" type="number" id="table-price" />
                            <span class='under_line'></span>
                        </div>

                        <div class="form-element input-empty">
                            <label class='input-label' for="take-away-price"> Take Away Price </label>
                            <input autocomplete="off" name="takeaway_price" class='mycustom-input' type="number" id="take-away-price" />
                            <span class='under_line'></span>
                        </div>

                        <div class="form-element input-empty">
                            <label class='input-label' for="dellvery-price"> Dellvery Price </label>
                            <input autocomplete="off" name="dellvery_price" class='mycustom-input' type="number" id="dellvery-price" />
                            <span class='under_line'></span>
                        </div>

                        <div class="form-element input-empty">
                            <label class='input-label' for="cost-price"> Cost Price </label>
                            <input autocomplete="off" name="cost_price" class='mycustom-input' type="number" id="cost-price" />
                            <span class='under_line'></span>
                        </div>
                        <div class="form-element input-empty">
                            <label class='input-label' for="during-time"> During Time </label>
                            <input autocomplete="off" name="during_time" class='mycustom-input' type="number" id="during-time" />
                            <span class='under_line'></span>
                        </div>
                        <div class="form-element input-empty">
                            <label class='input-label' for="calories-time"> calories </label>
                            <input autocomplete="off" name="calories_time" class='mycustom-input' type="number" id="calories-time" />
                            <span class='under_line'></span>
                        </div>

                    </div>

                    <div class='d-flex align-items-center'>

                        <div class='flex-grow-1 mr-4'>
                            <div class="form-element input-empty">
                                <label class='input-label' for="Items-wight">Items Wight</label>
                                <input autocomplete="off" name="wight" class='mycustom-input' type="number" id="Items-wight" />
                                <span class='under_line'></span>
                            </div>
                        </div>

                        <div class='flex-grow-1 '>
                            <div class="form-element m-0 input-empty">
                                <div class="select-box">
                                    <select class="select_and_Search_units" name="unit" id="unit">
                                        <option value="Kg">kg</option>
                                        <option value="gm">gm</option>
                                        <option value="L">L</option>
                                        <option value="M.l">M.L</option>
                                    </select>

                                    <div class='search-select'>
                                        <div class='label-Select'>
                                            <label for='unit-input'>Choose Unit...</label>
                                            <input autocomplete="off" type='text' class="search-input" id='unit-input' />
                                            <i class='arrow'></i>
                                            <span class='line'></span>
                                        </div>

                                        <div class='input-options'>
                                            <ul></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="custom-control custom-switch flex-grow-1 mt-4 ml-4">
                            <input type="checkbox" class="custom-control-input"  id="extra" name="extra" >
                            <label class="custom-control-label" for="extra"> Extra </label>
                        </div>
                        <div class="custom-control custom-switch flex-grow-1 mt-4 ml-4">
                            <input type="checkbox" class="custom-control-input"  id="active" name="active" checked >
                            <label class="custom-control-label" for="active"> Active </label>
                        </div>


                    </div>

                </div>

                <div class='col-md-5'>
                    <select class="custom-select mb-3"  name="printers[]" id="printers" multiple>
                        @foreach($printers as $printer)
                            <option value='{{ $printer->printer }}'>{{ $printer->printer }}</option>
                        @endforeach
                    </select>
                    <!-- Upload Image -->
                    <div class='text-center'>
                        <i class="file-image">
                            <input autocomplete="off" id="image" name="image" type="file" onchange="readImage(this)" title="" />
                            <i class="reset" onclick="resetImage(this.previousElementSibling)"></i>
                            <div id='item-image'>
                                <label for="image" class="image" data-label="Add Image"></label>
                            </div>
                        </i>
                    </div>

                        <div class="form-element input-empty">
                            <label class='input-label' for="barcode"> Add Barcode </label>
                            <input autocomplete="off" class='mycustom-input' id="barcode" name="barcode" type="text" />
                            <span class='under_line'></span>
                        </div>

                    <div class="form-element h-auto input-empty">
                        <label class='input-label' for="note">Note</label>
                        <textarea class='mycustom-input' id="note" name="note" type="text"></textarea>
                        <span class='under_line'></span>
                    </div>

                </div>

            </div>
            <div class="col-md-6 offset-md-3">
                <button id="save_item" type="submit" class="btn-block btn btn-success mt-4">Save</button>
                <button id="update_item" type="submit" class="btn-block btn btn-primary mt-4 d-none">Update</button>
                <button id="delete_item" type="submit" class="btn-block btn btn-danger mt-4 d-none">Delete</button>
                <button id="item_without" type="button" class="btn-block btn btn-secondary mt-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Item Without Printers</button>
                <button id="show_items" type="button" class="btn-block btn btn-warning" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Show Items</button>
                <button id="update_items_price" type="button" class="btn-block btn btn-info" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Update Price</button>
            </div>
        </form>
    </div>
    <!-- Modal Item With out Printers -->
    <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title label-model" id="staticBackdropLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="listofitem">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</section>
@include('includes.control.main')
@include('includes.control.item')
@stop
