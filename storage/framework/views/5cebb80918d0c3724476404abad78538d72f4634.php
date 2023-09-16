<div class='all-menus'>
    <!-- Start Options Menu -->
    <div id="options-menu">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("menu")): ?>
        <a href='#' class="options-item" data-toggle="modal" data-target="#menus">Menus</a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Open Add Item')): ?>
        <a href='#' class="options-item" data-toggle="modal" data-target="#create_item">Add Item</a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("copy check")): ?>
        <a href="<?php echo e(Route('copy.check')); ?>" class="options-item">Copy Check</a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("copy close shift")): ?>
            <a href="<?php echo e(Route('copy.close_shift')); ?>" class="options-item">Copy Shift</a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("discount")): ?>
        <a href='#' class="options-item discount-all-check" onclick="checkDiscountConfirm();">Discount</a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("move to")): ?>
        <a href="<?php echo e(Route('move.to')); ?>" class="options-item">Move To</a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("guests")): ?>
        <a href='#' class="options-item" data-target="#min_charge_modal_menu" data-toggle="modal" >Guests</a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("close shift")): ?>
        <a href="#" type="Close Shift" value='close_shift' class="close_shift_day options-item">Close Shift</a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("close day")): ?>
        <a href="#"  type="Close Day" value='close_day' class="close_shift_day options-item">Close Day</a>
        <?php endif; ?>
    </div>
    <!-- Start Options Menu -->
    <!-- Start Delivery Menu -->
    <div id="delivery-menu">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("delivery orders")): ?>
        <a  href="<?php echo e(Route('Delivery.Order')); ?>" idpage="delivery_order" class="delivery-item deliveryOrder" >Delivery Order</a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("to pilot")): ?>
        <a class="delivery-item to-pilot-btn toPilot" idpage="to_pilot" href="<?php echo e(Route('delivery.to.pilot')); ?>">To pilot
            <?php if($del_noti_to_pilot > 0): ?>
                <span class='notification-num'><?php echo e($del_noti_to_pilot); ?></span>
            <?php else: ?>
                <span class='notification-num del'>0</span>
            <?php endif; ?>
        </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("pilot account")): ?>
        <a href="<?php echo e(Route('delivery.pilot.account')); ?>" idpage="pilot_account" class="delivery-item pilot-acc">Pilot Account
            <?php if($del_noti_pilot > 0): ?>
                <span class='notification-num'><?php echo e($del_noti_pilot); ?></span>
            <?php else: ?>
                <span class='notification-num del'>0</span>
            <?php endif; ?>
        </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("hold delivery")): ?>
        <a data-toggle="modal" data-target="#hold-model" href='#' class="delivery-item" >Hold</a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("hold delivery list")): ?>
        <a href="<?php echo e(Route('delivery.hold.list')); ?>" idpage="hold_order"  class="delivery-item holding-list-btn" >Holding List
            <?php if($del_noti_hold > 0): ?>
                <span class='notification-num'><?php echo e($del_noti_hold); ?></span>
            <?php else: ?>
                <span class='notification-num del'>0</span>
            <?php endif; ?>
        </a>
        <?php endif; ?>
    </div>
    <!-- Start Delivery Menu -->

    <!-- Start Takeaway Menu -->
    <div id="takeaway-menu">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("to-go orders")): ?>
        <a  href="<?php echo e(Route('view.togo.Order')); ?>" idpage="hold_togo"  class="takeaway-item togoOrder">TO GO Order</a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("hold to-go")): ?>
        <a data-toggle="modal" data-target="#hold-model" class="takeaway-item" href="#">Hold</a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("hold to-go list")): ?>
        <a href="<?php echo e(Route('togo.hold.list')); ?>" idpage="hold_togo" class="takeaway-item holdingList" >Holding List
            <?php if($to_noti_hold > 0): ?>
                <span class='notification-num'><?php echo e($to_noti_hold); ?></span>
            <?php else: ?>
                <span class='notification-num del'>0</span>
            <?php endif; ?>
        </a>
        <?php endif; ?>
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
                    url: "<?php echo e(route('close.shift')); ?>",
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
                                showConfirmButton: false,
                                timer: 2250
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
                url: "<?php echo e(route('upload.device')); ?>",
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
<?php /**PATH D:\MyWork\Res\ERP\webPoint\resources\views/includes/menu/sub_header.blade.php ENDPATH**/ ?>