<script type="text/javascript">
	let _token = $('input[name="_token"]').val();


$(document).on('click','#save_togo',function (e)
{
    let formData      = new FormData($('#form_save_togo')[0]);
    e.preventDefault();
    $.ajax({
        url:"<?php echo e(route('save.togo')); ?>",
        method:'post',
        enctype:"multipart/form-data",
        processData:false,
        cache : false,
        contentType:false,
        'data' : formData,
        success: function (data)
        {
            if(data.status == true)
            {
            	Swal.fire({
                    position: 'center-center',
                    icon: 'success',
                    title: 'Your Items has been saved',
                    showConfirmButton: false,
                    timer: 1250
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




$(document).ready(function()
    {
        let _token           = $('input[name="_token"]').val();
        $('#tax').empty();
        $('#select_branch').on('change',function ()
        {
            $(this).parents('form').find('input[type="number"]').not('input[name="_token"]').each(function () {
                $(this).val('');
            });

            $(this).parents('form').find('input:checked').each(function () {
                $(this).prop('checked', false)
            });

            $('#printers-input').val('');

            let branch = $(this).val();
            if(branch)
            {
                $.ajax({
                    'type':'POST',
                    'url':"<?php echo e(Route('get.togo')); ?>",
                    'data':
                    {
                        'branch'  : branch,
                        '_token': "<?php echo e(csrf_token()); ?>",
                    },
                    success:function(data)
                    {

                        if(data[0])
                        {

                        if (data[0].print_slip) {
                            $('#slip').prop('checked', true);
                        }

                        if (data[0].print_togo) {
                            $('#takeaway-receipt').prop('checked', true);
                        }


                        if (data[0].display_checkout_screen) {
                            $('#cust-checkout-screen').prop('checked', true);
                        }

                        if (data[0].print_reservation_receipt) {
                            $('#reservation-receipt').prop('checked', true);
                        }
                        if (data[0].discount_tax_service == 1) {
                            $('#With_tax_service').prop('checked', true);
                        }else{
                            $('#Without_tax_service').prop('checked', true);
                        }
                        if (data[0].print_invice) {
                            $('#print-invoice').prop('checked', true);
                        }


                        if (data[0].fast_check) {
                            $('#fast-check').prop('checked', true);
                        }


                        if (data[0].convert_togo_table) {
                            $('#takeaway-to-table').prop('checked', true);
                        }

                        $('#tax').focus().val(data[0].tax);
                        $('#invoice-copies').focus().val(data[0].invoice_copies);
                        $('#service-ratio').focus().val(data[0].service_ratio);
                        $('#printers-input').focus().val(data[0].printer);
                        }

                    },
                });
            }
        });
    });

</script>
<?php /**PATH C:\xampp\htdocs\webpoint\resources\views/includes/control/Togo.blade.php ENDPATH**/ ?>