<script type="text/javascript">
	let _token = $('input[name="_token"]').val();
{{--###################### Start Save  ##################################### --}}

$(document).on('click','#save_delivery',function (e)
{
    let formData      = new FormData($('#form_save_delivery')[0]);
    e.preventDefault();
    $.ajax({
        url:"{{route('save.del')}}",
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
                    'url':"{{Route('get.del')}}",
                    'data':
                    {
                        'branch'  : branch,
                        '_token': "{{csrf_token()}}",
                    },
                    success:function(data)
                    {

                        if(data[0])
                        {
                            $('#tax').focus().val(data[0].tax);
                        if (data[0].type_ser === 'location') {
                            $('#ser-by-location').prop('checked', true);
                        } else {
                            $('#ser-by-Street').prop('checked', true);
                        }

                        if (data[0].print_slip) {
                            $('#pr-slip').prop('checked', true);
                        }

                        if (data[0].user_slip) {
                            $('#de-slip').prop('checked', true);
                        }
                        if (data[0].discount_tax_service == 1) {
                            $('#With_tax_service').prop('checked', true);
                        }else{
                            $('#Without_tax_service').prop('checked', true);
                        }

                        if (data[0].print_pilot_slip) {
                            $('#pr-invoice').prop('checked', true);
                        }

                        if (data[0].print_invoice) {
                            $('#print-invoice').prop('checked', true);
                        }


                        $('#ser-ratio').focus().val(data[0].ser_ratio);
                        $('#min-val').focus().val(data[0].min_val);
                        $('#max-val').focus().val(data[0].max_val);


                        $('#printers-input').focus().val(data[0].printer);
                        $('#delivery-ser').focus().val(data[0].del_service);
                        $('#fo-cop-no').focus().val(data[0].pilot_copies);
                        $('#invoic-copies').focus().val(data[0].Pay_copies)
                        }

                    },
                });
            }
        });
    });
{{--###################### End DElivety ####################################### --}}
</script>
