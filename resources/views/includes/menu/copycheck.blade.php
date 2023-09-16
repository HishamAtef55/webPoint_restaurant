<script>
    let _token = $('input[name="_token"]').val();
    $('#view_check').on('click', function(e){

        let orderNum = $('#order_num').val();
        let CopyItem = $('.custom-list').find('li');
        let serial = $('#serial').val();

        $.ajax({
            type    : 'POST',
            url     :"{{route('view.check')}}",
            method  : 'post',
            enctype : "multipart/form-data",
            data:
                {
                    _token : _token,
                    order : orderNum,
                    serial:serial,
                },
            success: function (data)
            {

                let html = '';
                let subtotal = 0;
                CopyItem.each(function() {
                    let method = $(this).find('span:last-child').attr('method')
                    $(this).find('span:last-child').text(`${data.orders[method]}`)
                });
                for(var count = 0 ; count < data.orders.wait_orders.length ; count ++)
                    {
                       // subtotal += data.wait_orders[count].total + data.wait_orders[count].total_extra + data.wait_orders[count].price_details - data.wait_orders[count].total_discount
                       html+='<li>';
                            html+='<span>'+data.orders.wait_orders[count].quantity + "<span style='color: var(--price-color)'> | </span>" + data.orders.wait_orders[count].name+'</span>';
                            html+='<input type="number" value="'+data.orders.wait_orders[count].total+'" disabled />';
                        if(data.orders.wait_orders[count].total_discount > 0)
                        {
                            html+='<div class="discount">';
                                html+='<div class="discount-name">';
                                    html+='<span>Discount</span>';
                                    html+='<span>'+data.orders.wait_orders[count].discount_name+'</span>';
                                html+='</div>';
                                html += `<div class="text-right flex-grow-1">${data.orders.wait_orders[count].total_discount}</div>`
                            html+='</div>';
                        }


                        for(var detail = 0 ; detail < data.orders.wait_orders[count].details.length ; detail ++)
                        {
                            html+='<div class="details">';
                                html+='<div class="details_name">';
                                    html+='<span>Detail</span>';
                                    html+='<span>'+data.orders.wait_orders[count].details[detail].name+'</span>';
                                html+='</div>';
                                html += `<div class="text-right flex-grow-1">${data.orders.wait_orders[count].details[detail].price * data.orders.wait_orders[count].quantity}</div>`
                            html+='</div>';
                        }

                        for(let extra= 0 ; extra < data.orders.wait_orders[count].extra.length ; extra ++)
                        {
                            html+='<div class="extra">';
                                html+='<div class="extra-name">';
                                    html+='<span>Extra</span>';
                                    html+='<span>'+data.orders.wait_orders[count].extra[extra].name+'</span>';
                                html+='</div>';
                                html += `<div class="text-right flex-grow-1">${data.orders.wait_orders[count].extra[extra].price * data.orders.wait_orders[count].quantity}</div>`
                            html+='</div>';
                        }

                        if(data.orders.wait_orders[count].comment)
                        {
                            html+='<div class="comment">';
                                html+='<div class="comment-name">';
                                    html+='<span>Comment</span>';
                                    html += `<pre class="text-right flex-grow-1">${data.orders.wait_orders[count].comment}</pre>`
                                html+='</div>';
                            html+='</div>';
                        }
                       html+='</li>';
                    }
                // let total = 0;
                if (data.orders.cashier) {
                    $('#cashier').text(data.orders.cashier.name);
                }else{
                    $('#cashier').text(' ')
                }
                $('#shift').text(data.orders.shift.shift);
                $('#sub_total').text(data.orders.sub_total);
                $('#service').html(data.orders.services);
                $('#tax').html(data.orders.tax);
                $('#discount').html(data.orders.total_discount);
                $('#new_order_view').html(html);
                $('#total').html(parseFloat(data.orders.total).toFixed(2));

            },
            error: function (reject) {
                CopyItem.each(function() {
                    $(this).find('span:last-child').text('')
                });
                $('#new_order_view').html('');
            }
        });
    });

    $('#copy_check').on('click',function(e){
        e.preventDefault();
        let order = $('#order_num').val();
        let devId = localStorage.getItem("device_number");
        let serial = $('#serial').val();
        $.ajax({
            type    : 'POST',
            url     :"{{route('print_copy_check')}}",
            method  : 'post',
            enctype : "multipart/form-data",
            data:
                {
                    _token,
                    order,
                    devId,
                    serial
                },
            success: function (data)
            {
                Swal.fire({
                    position: 'center-center',
                    icon: 'success',
                    title: 'Printed',
                    showConfirmButton: false,
                    timer: 1250
                });
            }
        })
    })
</script>
