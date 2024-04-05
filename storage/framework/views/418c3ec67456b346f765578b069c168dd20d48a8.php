<script>
    // When Operation Is Deleviry And Order IN None Disabled Take Order Btn
    function checkOrder() {
        let takeOrderBtn = $('a#take_order');
        let operation = $('#operation').attr('value');
        let newOrder = $('#new_order').attr('value');

        if (operation === 'Delivery' && newOrder === '') {
            takeOrderBtn.each(function () {
                $(this).addClass('disabled');
            });
        } else {
            takeOrderBtn.each(function () {
                $(this).removeClass('disabled');
            });
        }
    }


    $('#Customer-model').on('show.bs.modal', function () {
        let modal = $(this);
        let deviceId = $('input#device_id').val()
        modal.find('#device').val(deviceId);
        setTimeout(function() { modal.find('input.form-control.search').focus() }, 500);
    });
    
    $(document).on('click','#save_customer',function (e)
    {
        let inputs = Array.from($(this).parents("#Customer-model").find(".form-row input"));
        let customer    = $(this).parents('form').find('#cus_name').val();
        let formData      = new FormData($('#form_save_customer')[0]);
        e.preventDefault();
        $.ajax({
            url:"<?php echo e(route('Save.customer')); ?>",
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
                    $('.check .table-info .cusName').remove();
                    let html =''
                    html += `
                        <div class='cusName'>
                            <span>Customer</span>
                            <span>${customer}</span>
                        </div>
                    `;
                    $('.check .table-info').append(html)

                    $('input[name="search"]').val("")
                    inputs.forEach( input => {
                        input.value = ''
                    });
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Your Items has been saved',
                        showConfirmButton: false,
                        timer: 1250
                    });
                    $('#new_order').attr('value',data.order)
                    checkOrder();
                    $('#Customer-model').modal('hide')
                }
                if(data.status == 'false'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.msg,
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
    

    
    $('.search').on('input',function ()
    {
        var html = '';
        let query = $(this).val();
        let ID    = $('#branch').val();
        let type    = $('input[name="search_"]:checked').val();
        $('tbody').empty()
        if(query != '')
        {
            $('#tbody').show();
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url:"<?php echo e(route('search.customer')); ?>",
                method:'post',
                data:{ID:ID,type:type, query:query, _token:_token},
                success:function(data)
                {
                    if(type == 'search_name' || type =='search_location')
                    {
                        for(var count = 0 ; count < data.length ; count ++)
                        {
                            for(let x = 0 ; x < data[count].phones.length; x++)
                            {
                                html+=`<tr id_customer="${data[count].id}" id_phone="${data[count].phones[x].id}">`
                                html+=`<td data-value='cus_phone'> ${data[count].phones[x].phone} </td>`
                                html+=`<td data-value='cus_name'>${data[count].name}</td>`
                                html+=`<td data-value='cus_location'>${data[count].location}</td>`
                                html+=`<td data-value='cus_location_id' class='d-none'>${data[count].location_id}</td>`
                                html+=`<td data-value='cus_street' class='d-none'>${data[count].street}</td>`
                                html+=`<td data-value='cus_address' class='d-none'>${data[count].address}</td>`
                                html+=`<td data-value="special_marque" class='d-none'>${data[count].special_marque}</td>`
                                html+=`<td data-value='cus_role' class='d-none'>${data[count].role}</td>`
                                html+=`<td data-value='cus_department' class='d-none'>${data[count].department}</td>`
                                html+=`</tr>`
                            }

                        }
                        $('tbody').html(html);
                    }
                    else
                    {
                        for(var count = 0 ; count < data.length ; count ++)
                        {
                                html+=`<tr id_customer="${data[count].customer_id}" id_phone="${data[count].id}">`
                                html+=`<td data-value='cus_phone'> ${data[count].phone} </td>`
                                html+=`<td data-value='cus_name'>${data[count].customer.name}</td>`
                                html+=`<td data-value='cus_location'>${data[count].customer.location}</td>`
                                html+=`<td data-value='cus_location_id' class='d-none'>${data[count].customer.location_id}</td>`
                                html+=`<td data-value='cus_street' class='d-none'>${data[count].customer.street}</td>`
                                html+=`<td data-value='cus_address' class='d-none'>${data[count].customer.address}</td>`
                                html+=`<td data-value="special_marque" class='d-none'>${data[count].customer.special_marque}</td>`
                                html+=`<td data-value='cus_role' class='d-none'>${data[count].customer.role}</td>`
                                html+=`<td data-value='cus_department' class='d-none'>${data[count].customer.department}</td>`
                                html+=`<td data-value='id_phone' class='d-none'>${data[count].id}</td>`
                            html+=`</tr>`

                        }
                        $('tbody').html(html);
                    }
                }
            });
        }
    });
    


    
    $(document).on('click','#update_customer',function (e)
    {
        let formData      = new FormData($('#form_save_customer')[0]);
        e.preventDefault();
        $.ajax({
            url:"<?php echo e(route('update.customer')); ?>",
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
    


    
    $(document).on('click','#order_customer',function (e)
    {
        let customer_id = $(this).parents('form').find('#row_id').val();
        let customer    = $(this).parents('form').find('#cus_name').val();
        let location    = $('#cus_location').val();
        let order_id    = $('#new_order').attr('value');
        let dev         = $('#device_id').attr('value');
        let _token      = $('input[name="_token"]').val();
        let state       = $('#Edit_customer').attr('value');
        let op          = $('#operation').attr('value');
        e.preventDefault();
        $.ajax({
            url:"<?php echo e(route('order.customer')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token      : _token,
                    order_id    : order_id,
                    customer_id : customer_id,
                    customer    : customer,
                    state       : state,
                    location    : location,
                    op          : op,
                    dev         : dev
                },
            success: function (data)
            {
                if(data.status == true)
                {
                    $('#new_order').attr('value',data.order)
                    $('#delivery_val').attr('value',data.delivery)
                    $('#Customer-model').modal('hide')
                    checkOrder();

                    $('.check .table-info .cusName').remove();
                    let html =''
                    html += `
                        <div class='cusName'>
                            <span>Customer</span>
                            <span>${customer}</span>
                        </div>
                    `;
                    $('.check .table-info').append(html)
                }
            },
        });
    });
    

    $('#add_location').on('click', function() {

        let locationName = $('#locationName');
        let locationPrice = $('#locationPrice');
        let locationTime = $('#locationTime');
        let pilotValue = $('#pilotValue');
        let _token = $('input[name="_token"]').val();
        let branch = $('#branch').val();

        $.ajax({
            url: "<?php echo e(route('save.location')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token   : _token,
                    location : locationName.val(),
                    price    : locationPrice.val(),
                    time     : locationTime.val(),
                    branch   : branch,
                    pilotValue:pilotValue.val()
                },
            success: function (data) {
                if(data.status == 'true')
                {
                    let html = `<option price="${locationPrice.val()}" time="${locationTime.val()}" value="${data.id}">${locationName.val()}</option>`;
                    $('#cus_location').append($(html));
                    $('#addLocationModal').modal('hide');
                    locationName.val('')
                    locationPrice.val('')
                    locationTime.val('')
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
<?php /**PATH D:\Xampp\htdocs\webpoint\resources\views/includes/menu/customer.blade.php ENDPATH**/ ?>