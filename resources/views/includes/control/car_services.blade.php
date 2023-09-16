<script type="text/javascript">
	let _token = $('input[name="_token"]').val();
{{--###################### Start Save  ##################################### --}}

$(document).on('click','#save_car_Services',function (e)
{
    let formData      = new FormData($('#form_save_car_services')[0]);
    e.preventDefault();
    $.ajax({
        url:"{{route('save.car.cervices')}}",
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

            $('#printers_input').val('');

            let branch = $(this).val();
            if(branch)
            {
                $.ajax({
                    'type':'POST',
                    'url':"{{Route('get.car.cervices')}}",
                    'data':
                    {
                        'branch'  : branch,
                        '_token': "{{csrf_token()}}",
                    },
                    success:function(data)
                    {

                        if(data[0])
                        {

                        if (data[0].slip) {
                            $('#slip').prop('checked', true);
                        }

                        if (data[0].car_service_receipt) {
                            $('#car_service_receipt').prop('checked', true);
                        }


                        if (data[0].reservation_receipt) {
                            $('#reservation_receipt').prop('checked', true);
                        }

                        if (data[0].print_invoice) {
                            $('#print_invoice').prop('checked', true);
                        }

                        if (data[0].fast_check) {
                            $('#fast_check').prop('checked', true);
                        }


                        $('#tax').val(data[0].tax);
                        $('#invoice_copies').val(data[0].invoice_copies);
                        $('#service_ratio').val(data[0].service_ratio);
                        $('#printers_input').val(data[0].printers_input);
                        }

                    },
                });
            }
        });
    });
{{--###################### End DElivety ####################################### --}}
</script>
