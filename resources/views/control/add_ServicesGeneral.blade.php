@php
$title = 'Add Services General';
@endphp

@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section>
            <div class='container'>
                <div class='row'>
                    <!-- Start Items Accordion -->
                    <div class='col-xl-8 offset-xl-2 col-lg-10 offset-lg-1'>
                        <div class="card-items p-4 mb-5">
                            <h2 class='text-center'> General </h2>
                            <form>
                                <div class='row'>
                                  <div class='col-md-6'>
                                    <div class='form-element'>
                                        <label class='input-label' for="company-name">Company Name</label>
                                        <input type="text" class="mycustom-input" id="company-name" />
                                        <span></span>
                                    </div>
                                  </div>
                                  <div class='col-md-6'>
                                    <div class='form-element'>
                                        <label class='input-label' for="ERC-No">ERC No.</label>
                                        <input type="text" class="mycustom-input" id="ERC-No" />
                                        <span></span>
                                    </div>
                                  </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-element">
                                            <select  class="form-element-field">
                                                <option disabled selected value="" class="form-select-placeholder"></option>
                                                <option value="_">One</option>
                                                <option value="_">Two</option>
                                                <option value="_">Three</option>
                                                <option value="_">Four</option>
                                            </select>
                                            <span></span>
                                            <label class="form-element-label">Menu Name</label>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class='form-element'>
                                            <label class='input-label' for="TIN-No">TIN No.</label>
                                            <input type="text" class="mycustom-input" id="TIN-No" />
                                            <span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-element">
                                            <select  class="form-element-field">
                                                <option disabled selected value="" class="form-select-placeholder"></option>
                                                <option value="_">One</option>
                                                <option value="_">Two</option>
                                                <option value="_">Three</option>
                                                <option value="_">Four</option>
                                            </select>
                                            <span></span>
                                            <label class="form-element-label">Branch Name</label>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class='form-element'>
                                            <label class='input-label' for="hours">Hours No.</label>
                                            <input type="text" class="mycustom-input" id="hours" />
                                            <span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class='form-element'>
                                            <label class='input-label' for="serial">Serial Start</label>
                                            <input type="text" class="mycustom-input" id="serial" />
                                            <span></span>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class='form-element'>
                                            <label class='input-label' for="pos">Pos No.</label>
                                            <input type="text" class="mycustom-input" id="pos" />
                                            <span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-element">
                                            <select  class="form-element-field">
                                                <option disabled selected value="" class="form-select-placeholder"></option>
                                                <option value="1">English</option>
                                                <option value="2">Arabic</option>
                                                <option value="3">Russia</option>
                                            </select>
                                            <span></span>
                                            <label class="form-element-label">Language</label>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class='radios mt-5 mb-5'>
                                            <div class="shadow p-3 rounded d-block">Close Shift Over</div>
                                            <div class='radio-box p-3 shadow'>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="exampleRadios" id="all-system" value="option1" checked>
                                                    <label class="form-check-label" for="all-system">All System</label>
                                                </div>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="radio" name="exampleRadios" id="pos-machine" value="option1" >
                                                    <label class="form-check-label" for="pos-machine">Pos Machine</label>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class='form-action d-flex m-n4'>
                                    <button type="submit" class="form-btn col bg-success">Save</button>
                                    <button type="submit" class="form-btn col bg-primary">Update</button>
                                </div>
                            </form>
                        </div>
                        <!-- End Items Accordion -->
                    </div>
                </div>
            </div>
        </section>     
@stop