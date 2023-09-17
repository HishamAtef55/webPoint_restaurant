@php
$title = 'User';
@endphp
@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section>
    <div class='container'>
        <h2 class="section-title"> User </h2>

        <form action=" " id="form_save_user" method="post" enctype="multipart/form-data">
            <div class="row mr-0 ml-0">
                @csrf

                <div class='col-md-5'>

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


                    <div class="form-element input-empty">
                        <label class='input-label' for="user-name">User Name</label>
                        <input autocomplete="off" name="name" class='mycustom-input' type="text" id="user-name" />
                        <span class='under_line'></span>
                    </div>

                    <div class="form-element input-empty">
                        <label class='input-label' for="password">Password</label>
                        <input autocomplete="off" name="password" class='mycustom-input' type="password"
                            id="password" />
                        <span class='under_line'></span>
                    </div>

                    <div class="form-element input-empty">
                        <label class='input-label' for="confirm-password">Confirm Password</label>
                        <input class='mycustom-input' name="confirm_password" type="password" id="confirm-password" />
                        <span class='under_line'></span>
                    </div>



                    <div class="d-flex align-items-center">
                        <div class="form-element input-empty flex-grow-1 mr-4">
                            <label class='input-label' for="email">Email</label>
                            <input autocomplete="off" name="email" class='mycustom-input' type="email" id="email" />
                            <span class='under_line'></span>
                        </div>

                        <div class="form-element input-empty flex-grow-1">
                            <label class='input-label' for="email">Phone</label>
                            <input autocomplete="off" name="mopile" class='mycustom-input' type="text" id="mopile" />
                            <span class='under_line'></span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="form-element input-empty flex-grow-1 mr-4">
                            <label class='input-label' for="discount-ratio">Discount Ratio</label>
                            <input autocomplete="off" name="discount_ratio" class='mycustom-input' type="text"
                                id="discount-ratio" />
                            <span class='under_line'></span>
                        </div>

                        <div class="form-element input-empty flex-grow-1">
                            <label class='input-label' for="dialy-salary">Daily Salary</label>
                            <input autocomplete="off" name="dialy_salary" class='mycustom-input' type="text"
                                id="dialy-salary" />
                            <span class='under_line'></span>
                        </div>
                    </div>

                </div>

                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-5">
                            <div class='radios mt-3 mb-3'>
                                <div class="shadow p-3 rounded d-block">Position</div>
                                <div class='radio-box p-3 shadow'>
                                    @foreach($jobs as $job)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="position" id="{{$job->id}}"
                                            value="{{$job->id}}">
                                        <label class="form-check-label" for="{{$job->id}}">{{$job->name}}</label>
                                    </div>
                                    @endforeach
                                    <small id="position_error" class="form-text text-danger"></small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class='radios mt-3 mb-3'>
                                <div class="shadow p-3 rounded d-block">Access System</div>
                                <div class='radio-box p-3 shadow'>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="pos"
                                            value="pos">
                                        <label class="form-check-label" for="pos">POS</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="stock"
                                            value="stock">
                                        <label class="form-check-label" for="stock">Stock</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="hr"
                                            value="hr">
                                        <label class="form-check-label" for="hr">HR</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="accounting"
                                            value="accounting">
                                        <label class="form-check-label" for="accounting">Accounting</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="type[]" id="dashboard"
                                            value="dashboard">
                                        <label class="form-check-label" for="dashboard">Dashboard</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='col-md-4'>
                            <!-- Upload Image -->
                            <div class='text-center mt-5'>
                                <i class="file-image">
                                    <input autocomplete="off" id="image" type="file" name="image" onchange="readImage(this)" title="" />
                                    <i class="reset" onclick="resetImage(this.previousElementSibling)"></i>
                                    <div id='item-image'>
                                <label for="image" class="image" data-label="Add Image"></label>
                            </div>
                                </i>
                            </div>

                        </div>

                        <button id="save_user" type="submit" class="btn btn-block btn-success mt-3">Save</button>
                    </div>
                </div>

            </div>

        </form>

        <div class="form-element">
            <label for="search" class="input-label"><i class="fas fa-search"></i> Search for Users</label>
            <input autocomplete="off" type="text" name="search" id="search" class="mycustom-input">
            <span class='under_line'></span>
        </div>

        @csrf

        <div class="table-responsive">
            <table id="editable" class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Salary</th>
                        <th scope="col">Pass</th>
                        <th scope="col">Job</th>
                    </tr>
                </thead>
                <tbody id="tbody">

                </tbody>
            </table>
        </div>

    </div>
</section>
@include('includes.control.user')
@stop
