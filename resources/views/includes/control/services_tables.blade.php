<script type="text/javascript">
	let _token = $('input[name="_token"]').val();
{{--###################### Start Save  ##################################### --}}

$(document).on('click','#save_services_tables',function (e)
{
    let formData      = new FormData($('#form_save_services_tables')[0]);
    e.preventDefault();
    $.ajax({
        url:"{{route('save.ser.table')}}",
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

{{--###################### End Save ####################################### --}}

{{--###################### Get DElivety ####################################### --}}
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
                    'url':"{{Route('get.ser.table')}}",
                    'data':
                    {
                        'branch'  : branch,
                        '_token': "{{csrf_token()}}",
                    },
                    success:function(data)
                    {

                        if(data[0])
                        {

                        if (data[0].fast_checkout) {
                            $('#fast_checkout').prop('checked', true);
                        }

                        if (data[0].print_invoic) {
                            $('#print_invoic').prop('checked', true);
                        }


                        if (data[0].reser_recipt) {
                            $('#reser_recipt').prop('checked', true);
                        }
                        if (data[0].invoice_payment) {
                            $('#invoice_payment').prop('checked', true);
                        }
                        if (data[0].payment_teble) {
                            $('#payment_teble').prop('checked', true);
                        }
                        if (data[0].invoic_teble) {
                            $('#invoic_teble').prop('checked', true);
                        }
                        if (data[0].end_teble) {
                            $('#end_teble').prop('checked', true);
                        }
                        if (data[0].vou_copon) {
                            $('#vou_copon').prop('checked', true);
                        }
                        if (data[0].mincharge_screen) {
                            $('#mincharge_screen').prop('checked', true);
                        }
                        if (data[0].display_table) {
                            $('#display_table').prop('checked', true);
                        }
                        if (data[0].receipt_checkout) {
                            $('#receipt_checkout').prop('checked', true);
                        }
                        if (data[0].discount_tax_service == 1) {
                            $('#With_tax_service').prop('checked', true);
                        }else{
                            $('#Without_tax_service').prop('checked', true);
                        }
                        if (data[0].receipt_send) {
                            $('#receipt_send').prop('checked', true);
                        }
                        if (data[0].slip_all) {
                            $('#slip_all').prop('checked', true);
                        }
                        if (data[0].slip_copy) {
                            $('#slip_copy').prop('checked', true);
                        }
                        if (data[0].pr_reservation) {
                            $('#pr_reservation').prop('checked', true);
                        }
                        if (data[0].car_receipt) {
                            $('#car_receipt').prop('checked', true);
                        }
                        if (data[0].print_slip) {
                            $('#print_slip').prop('checked', true);
                        }
                        if (data[0].tax_service) {
                            $('#tax_service').prop('checked', true);
                        }

                        $('#printers-input').focus().val(data[0].printers_input);
                        $('#printers-input-shift').focus().val(data[0].printer_shift);
                        $('#invoic_copies').focus().val(data[0].invoic_copies);
                        $('#service_ratio').focus().val(data[0].service_ratio);
                        $('#bank-ratio').focus().val(data[0].r_bank);
                        $('#tax').focus().val(data[0].tax);
                        }

                    },
                });
            }
        });
    });
{{--###################### End DElivety ####################################### --}}
</script>
