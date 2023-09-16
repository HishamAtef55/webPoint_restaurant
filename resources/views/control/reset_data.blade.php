@php
    $title = 'Reset Data';
@endphp

@extends('layouts.app')
@section('content')
@include('layouts.nav_left')
<section class='accordions-sec'>
    <div class="container">
        <h2 class="section-title">Reset Data</h2>
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                {{-- <div class="select-box">
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
                </div> --}}
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                @csrf
                <button class="btn btn-primary reset-data" task="order_only" type="button">Reset Orders Only</button>
                <button class="btn btn-primary reset-data" task="all_data" type="button">Reset All Data</button>
            </div>
        </div>
    </div>
</section>
<script>
$('.reset-data').on('click',function (e) {
    e.preventDefault();
    let _token = $('input[name="_token"]').val();
    let Branch = $('#select').val();
    let op = $(this).attr('task')
    $.ajax({
        url: "{{route('reset_data_post')}}",
        method: 'post',
        enctype: "multipart/form-data",
        data:
            {
                _token,
                Branch,
                op,
            },
        success: function (data) {
            if(data.status == true)
            {
                Swal.fire({
                    position: 'center-center',
                    icon: 'success',
                    title: 'Your De has been saved',
                    showConfirmButton: false,
                    timer: 1000
                    });
            }
        },
        error: function (reject) {
        var response  = $.parseJSON(reject.responseText);
        $.each(response.errors , function (key, val)
        {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: val[0],
            });
        });
    }
    });
});
</script>
@stop
