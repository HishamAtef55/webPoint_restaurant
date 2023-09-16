<script type="text/javascript">
	let _token = $('input[name="_token"]').val();
{{--###################### Start Save  ##################################### --}}

$(document).on('click','#save_other',function (e)
{
    let formData      = new FormData($('#form_save_other')[0]);
    e.preventDefault();
    $.ajax({
        url:"{{route('save.other')}}",
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

        $('#select_branch').on('change',function ()
        {
            $(this).parents('form').find('input[type="number"]').not('input[name="_token"]').each(function () {
                $(this).val('');
            });

            $(this).parents('form').find('input:checked').each(function () {
                $(this).prop('checked', false)
            });

            $('#printers-input').val('');
            $('#select_printers_Invoice-input').val('');
            $('#Drawer').val('');
            $('#transaction_printer').val('');

            let branch = $(this).val();
            if(branch)
            {
                $.ajax({
                    'type':'POST',
                    'url':"{{Route('get.other')}}",
                    'data':
                    {
                        'branch'  : branch,
                        '_token': "{{csrf_token()}}",
                    },
                    success:function(data)
                    {
                        if(data[0])
                        {
                        console.log(data[0])
                        if (data[0].close_day_auto) {
                            $('#close-day-auto').prop('checked', true);
                        }

                        if (data[0].close_day_table) {
                            $('#close-day-table').prop('checked', true);
                        }

                        if (data[0].compo) {
                            $('#Combo').prop('checked', true);
                        }

                        if (data[0].promotions) {
                            $('#promotions').prop('checked', true);
                        }
                        if (data[0].allow_void) {
                            $('#allow-void').prop('checked', true);
                        }
                        if (data[0].allow_update) {
                            $('#allow-update').prop('checked', true);
                        }
                        if (data[0].void_priming) {
                            $('#void-priming').prop('checked', true);
                        }
                        if (data[0].display_modify) {
                            $('#dis-modify').prop('checked', true);
                        }
                        if (data[0].display_total) {
                            $('#dis-totals').prop('checked', true);
                        }
                        if (data[0].display_waiter) {
                            $('#dis-waiter').prop('checked', true);
                        }
                        if (data[0].item_tax) {
                            $('#item-tax').prop('checked', true);
                        }
                        if (data[0].item_service) {
                            $('#item-service').prop('checked', true);
                        }
                        if (data[0].display_addition) {
                            $('#dis-addition').prop('checked', true);
                        }
                        if (data[0].employees_shift) {
                            $('#Employees-shift').prop('checked', true);
                        }
                        if (data[0].malt_pass_security) {
                            $('#malt-pass-security').prop('checked', true);
                        }
                        if (data[0].time_attendance) {
                            $('#time-attendance').prop('checked', true);
                        }
                        if (data[0].over_sub) {
                            $('#over-sub').prop('checked', true);
                        }
                        if (data[0].display_visa) {
                            $('#dis-visa').prop('checked', true);
                        }
                        if (data[0].display_ledge) {
                            $('#dis-ledge').prop('checked', true);
                        }
                        if (data[0].display_officer) {
                            $('#dis-officer').prop('checked', true);
                        }
                        if (data[0].dis_hospitality) {
                            $('#dis-hospitality').prop('checked', true);
                        }
                        if (data[0].dis_save) {
                            $('#dis-save').prop('checked', true);
                        }
                        if (data[0].dis_save_print) {
                            $('#dis-save-print').prop('checked', true);
                        }
                        if (data[0].dis_keyboard) {
                            $('#dis-keyboard').prop('checked', true);
                        }
                        if (data[0].dis_tip_cash) {
                            $('#dis-tip-cash').prop('checked', true);
                        }

                        if (data[0].del_data) {
                            $('#del-data').prop('checked', true);
                        }
                        if (data[0].print_void_slip) {
                            $('#print-void-slip').prop('checked', true);
                        }
                        if (data[0].print_reports) {
                            $('#print-reports').prop('checked', true);
                        }
                        if (data[0].collect_items_check) {
                            $('#collect-items-check').prop('checked', true);
                        }
                        if (data[0].collect_items_slip) {
                            $('#collect-items-slip').prop('checked', true);
                        }
                        if (data[0].items_qty) {
                            $('#items-qty').prop('checked', true);
                        }
                        if (data[0].decimal_qty) {
                            $('#decimal-qty').prop('checked', true);
                        }
                        if (data[0].delivery_reciving_customer) {
                            $('#delivery-reciving-customer').prop('checked', true);
                        }
                        if (data[0].check_balance) {
                            $('#check-balance').prop('checked', true);
                        }
                        if (data[0].flash_reports) {
                            $('#flash-reports').prop('checked', true);
                        }
                        if (data[0].def_transaction) {
                            $('#def-transaction').prop('checked', true);
                        }
                        if (data[0].expeneses) {
                            $('#expeneses').prop('checked', true);
                        }
                        if (data[0].copy_invoice) {
                            $('#copy-invoice').prop('checked', true);
                        }
                        if (data[0].drawer_printer_check) {
                            $('#drawer-printer').prop('checked', true);
                        }

                        $('#transaction').val(data[0].transaction);
                        $('#Drawer').val(data[0].drawer_printer);
                        $('#close_day').val(data[0].close_day);
                        $('#printers-input').val(data[0].printer);
                        $('#reser-copies').val(data[0].reservation_copies);
                        $('#transaction').val(data[0].transaction_printer);
                        $('#transaction-copies').val(data[0].transaction_copies);
                        $('#fast_checkout').val(data[0].fast_checkout);
                        $('#select_printers_Invoice-input').val(data[0].printers_Invoice);
                        }
                    },
                });
            }
        });
    });
{{--###################### End DElivety ####################################### --}}
</script>
