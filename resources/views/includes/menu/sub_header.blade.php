<div class='all-menus'>
    <!-- Start Options Menu -->
    <div id="options-menu">
        @can("menu")
        <a href='#' class="options-item" data-toggle="modal" data-target="#menus">Menus</a>
        @endcan
        @can('Open Add Item')
        <a href='#' class="options-item" data-toggle="modal" data-target="#create_item">Add Item</a>
        @endcan
        @can("copy check")
        <a href="{{Route('copy.check')}}" class="options-item">Copy Check</a>
        @endcan
        @can("copy close shift")
            <a href="{{Route('copy.close_shift')}}" class="options-item">Copy Shift</a>
        @endcan
        @can("discount")
        <a href='#' class="options-item discount-all-check" onclick="checkDiscountConfirm();">Discount</a>
        @endcan
        @can("move to")
        <a href="{{Route('move.to')}}" class="options-item">Move To</a>
        @endcan
        @can("guests")
        <a href='#' class="options-item" data-target="#min_charge_modal_menu" data-toggle="modal" >Guests</a>
        @endcan
        @can("Expenses")
        <a href="{{Route('DailyExpenses')}}" class="options-item" >Expenses</a>
        @endcan
        @can("close shift")
        <a href="#" type="Close Shift" value='close_shift' class="close_shift_day options-item">Close Shift</a>
        @endcan
        @can("close day")
        <a href="#"  type="Close Day" value='close_day' class="close_shift_day options-item">Close Day</a>
        @endcan
    </div>
    <!-- Start Options Menu -->
    <!-- Start Delivery Menu -->
    <div id="delivery-menu">
        @can("delivery orders")
        <a  href="{{Route('Delivery.Order')}}" idpage="delivery_order" class="delivery-item deliveryOrder" >Delivery Order</a>
        @endcan
        @can("to pilot")
        <a class="delivery-item to-pilot-btn toPilot" idpage="to_pilot" href="{{Route('delivery.to.pilot')}}">To pilot
            @if($del_noti_to_pilot > 0)
                <span class='notification-num'>{{$del_noti_to_pilot}}</span>
            @else
                <span class='notification-num del'>0</span>
            @endif
        </a>
        @endcan
        @can("pilot account")
        <a href="{{Route('delivery.pilot.account')}}" idpage="pilot_account" class="delivery-item pilot-acc">Pilot Account
            @if($del_noti_pilot > 0)
                <span class='notification-num'>{{$del_noti_pilot}}</span>
            @else
                <span class='notification-num del'>0</span>
            @endif
        </a>
        @endcan
        @can("hold delivery")
        <a data-toggle="modal" data-target="#hold-model" href='#' class="delivery-item" >Hold</a>
        @endcan
        @can("hold delivery list")
        <a href="{{Route('delivery.hold.list')}}" idpage="hold_order"  class="delivery-item holding-list-btn" >Holding List
            @if($del_noti_hold > 0)
                <span class='notification-num'>{{$del_noti_hold}}</span>
            @else
                <span class='notification-num del'>0</span>
            @endif
        </a>
        @endcan
    </div>
    <!-- Start Delivery Menu -->

    <!-- Start Takeaway Menu -->
    <div id="takeaway-menu">
        @can("to-go orders")
        <a  href="{{Route('view.togo.Order')}}" idpage="hold_togo"  class="takeaway-item togoOrder">TO GO Order</a>
        @endcan
        @can("hold to-go")
        <a data-toggle="modal" data-target="#hold-model" class="takeaway-item" href="#">Hold</a>
        @endcan
        @can("hold to-go list")
        <a href="{{Route('togo.hold.list')}}" idpage="hold_togo" class="takeaway-item holdingList" >Holding List
            @if($to_noti_hold > 0)
                <span class='notification-num'>{{$to_noti_hold}}</span>
            @else
                <span class='notification-num del'>0</span>
            @endif
        </a>
        @endcan
    </div>
    <!-- Start Takeaway Menu -->
</div>

<script>
    $('.close_shift_day').on('click', function(e) {
        e.preventDefault();
        let _token           = $('input[name="_token"]').val();
        let type = $(this).attr('value');
        let typeStatus = $(this).attr('type');

        Swal.fire({
            title: typeStatus,
            text: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{route('close.shift')}}",
                    method: 'post',
                    enctype: "multipart/form-data",
                    data:
                        {
                            _token,
                            type,
                        },
                    success: function (data) {
                        if(data.status == "error"){
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data.msg,
                            });
                        }else if(data.status == "success"){
                            Swal.fire({
                                position: 'center-center',
                                icon: 'success',
                                title: data.msg,
                                showConfirmButton: true,
                            });
                        }
                    }
                });            }
        });

    });
    $(document).ready(function(){
        if(!localStorage.getItem("device_number")){
            $('#device-model').modal('show')
        }
        $('#save_dev_outadmin').on('click', function(e) {
            e.preventDefault();
            localStorage.setItem("device_number",$('#number_dev_inut').val());
            let _token = $('input[name="_token"]').val();
            let ID_DEV = $('#number_dev_inut').val();
            let Branch = 0;
            let op = 'outadmin';
            let printer = $('#device_printer').val();

            $.ajax({
                url: "{{route('upload.device')}}",
                method: 'post',
                enctype: "multipart/form-data",
                data:
                    {
                        ID_DEV   : ID_DEV,
                        _token   : _token,
                        Branch   : Branch,
                        op       : op,
                        printer   : printer

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
                }
            });
        });

    });

</script>
